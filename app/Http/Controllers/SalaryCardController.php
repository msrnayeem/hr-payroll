<?php

namespace App\Http\Controllers;

use App\Models\SalaryCard;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalaryCardHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\SalaryComponent;


class SalaryCardController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->can('add_salary_card')) {
            $salaryCards = SalaryCard::with(['employee', 'components'])->get();
        } else {
            $salaryCards = SalaryCard::with(['employee', 'components'])->where('employee_id', auth()->user()->id)->get();
        }

        return view('salary_cards.index', compact('salaryCards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('salary_card_id', null)->get();

        return view('salary_cards.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'components' => 'array',
            'components.*.type' => 'nullable|in:fixed,percentage',
            'components.*.value' => 'nullable|numeric|min:0',
        ]);

        $employee = User::findOrFail($request->employee_id);

        // Check if the employee already has a salary card
        if ($employee->salaryCard()->exists()) {
            return redirect()->back()->with('error', 'Salary card already exists for this employee.');
        }

        // Create Salary Card
        $salaryCard = new SalaryCard([
            'basic_salary' => $request->basic_salary,
            'net_salary' => 0,
            'total_deductions' => 0,
            'total_earnings' => 0,
            'created_by' => auth()->id(),
        ]);

        $employee->salaryCard()->save($salaryCard);

        // Attach Salary Components (if provided)
        if ($request->has('components')) {
            $componentsData = [];
            foreach ($request->components as $componentId => $data) {
                if (!empty($data['value']) && $data['value'] > 0) {
                    $calculationType = $data['type'] ?? 'fixed';
                    $originalValue = $data['value'];
                    $amount = $calculationType === 'percentage'
                        ? ($salaryCard->basic_salary * $originalValue / 100)
                        : $originalValue;

                    $componentsData[$componentId] = [
                        'amount' => $amount,
                        'calculation_type' => $calculationType,
                        'original_value' => $originalValue,
                    ];
                }
            }

            if (!empty($componentsData)) {
                $salaryCard->components()->attach($componentsData);
            }
        }

        // Calculate totals
        $salaryCard->calculateTotals();

        // Log history for salary card creation
        $newValues = [
            'employee_id' => $employee->id,
            'basic_salary' => $salaryCard->basic_salary,
            'net_salary' => $salaryCard->net_salary,
            'total_earnings' => $salaryCard->total_earnings,
            'total_deductions' => $salaryCard->total_deductions,
            'components' => $componentsData ?? [],
            'created_by' => auth()->id(),
        ];
        $this->logHistory($salaryCard, 'create', $newValues, null);

        return redirect()->route('salary-cards.index')->with('success', 'Salary card created successfully.');
    }


    public function show(SalaryCard $salaryCard)
    {
        // Eager load the user and components
        $salaryCard->load('employee', 'components');
        return view('salary_cards.show', compact('salaryCard'));
    }

    public function edit(SalaryCard $salaryCard)
    {
        //users where not null salary_card_id
        $users = User::get();
        return view('salary_cards.edit', compact('salaryCard', 'users'));
    }

    public function update(Request $request, SalaryCard $salaryCard)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'components' => 'array',
            'components.*.type' => 'nullable|in:fixed,percentage',
            'components.*.value' => 'nullable|numeric|min:0',
        ]);

        // Store old values for history logging
        $oldValues = [
            'employee_id' => $salaryCard->employee_id,
            'basic_salary' => $salaryCard->basic_salary,
            'net_salary' => $salaryCard->net_salary,
            'total_earnings' => $salaryCard->total_earnings,
            'total_deductions' => $salaryCard->total_deductions,
            'components' => $salaryCard->components->pluck('pivot')->all(),
        ];

        // Update basic salary and reset totals
        $salaryCard->update([
            'basic_salary' => $request->basic_salary,
            'net_salary' => 0,           // Reset to recalculate
            'total_earnings' => 0,       // Reset to recalculate
            'total_deductions' => 0,     // Reset to recalculate
        ]);

        // Handle components
        if ($request->has('components')) {
            $componentsData = [];
            foreach ($request->components as $componentId => $data) {
                if (!empty($data['value']) && $data['value'] > 0) {
                    $calculationType = $data['type'] ?? 'fixed';
                    $originalValue = $data['value'];
                    $amount = $calculationType === 'percentage'
                        ? ($salaryCard->basic_salary * $originalValue / 100)
                        : $originalValue;

                    $componentsData[$componentId] = [
                        'amount' => $amount,
                        'calculation_type' => $calculationType,
                        'original_value' => $originalValue,
                    ];
                }
            }

            // Sync components (this will remove old ones and add new ones)
            if (!empty($componentsData)) {
                $salaryCard->components()->sync($componentsData);
            } else {
                $salaryCard->components()->detach();
            }
        } else {
            $salaryCard->components()->detach();
        }

        // Refresh the model to get the latest data
        $salaryCard->refresh();

        // Calculate totals
        $salaryCard->calculateTotals();
        $salaryCard->save(); // Save the calculated totals

        // Log history for update
        $newValues = [
            'employee_id' => $salaryCard->employee_id,
            'basic_salary' => $salaryCard->basic_salary,
            'net_salary' => $salaryCard->net_salary,
            'total_earnings' => $salaryCard->total_earnings,
            'total_deductions' => $salaryCard->total_deductions,
            'components' => $componentsData ?? [],
            'updated_by' => auth()->id(),
        ];
        $this->logHistory($salaryCard, 'update', $newValues, $oldValues);

        return redirect()->route('salary-cards.index')->with('success', 'Salary card updated successfully.');
    }


    public function history(SalaryCard $salaryCard)
    {
        // Fetch history records with related user and changedBy data
        $histories = $salaryCard->histories()
            ->with(['employee', 'changedBy'])
            ->orderBy('changed_at', 'desc')
            ->get();

        // Fetch all salary components to map IDs to names and types
        $components = SalaryComponent::all()->keyBy('id');

        return view('salary_cards.history', compact('salaryCard', 'histories', 'components'));
    }

    private function logHistory(SalaryCard $salaryCard, string $action, array $newValues, ?array $oldValues = null): void
    {
        SalaryCardHistory::create([
            'salary_card_id' => $salaryCard->id,
            'employee_id' => $newValues['employee_id'] ?? $salaryCard->employee->id ?? null,
            'action' => $action,
            'old_values' => $oldValues, // Store raw array, not encoded string
            'new_values' => $newValues, // Store raw array, not encoded string
            'changed_at' => now(),
            'changed_by' => auth()->user()->id,
        ]);
    }
}
