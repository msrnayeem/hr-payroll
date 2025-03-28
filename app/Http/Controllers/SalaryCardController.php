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
        $salaryCards = SalaryCard::with(['user', 'components'])->get();
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
            'user_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'components' => 'array',
            'components.*.type' => 'nullable|in:fixed,percentage',
            'components.*.value' => 'nullable|numeric|min:0',
        ]);

        $salaryCard = SalaryCard::create([
            'basic_salary' => $request->basic_salary,
            'net_salary' => 0,
            'total_deductions' => 0,
            'total_earnings' => 0,
        ]);

        $componentsData = [];
        if ($request->has('components')) {
            foreach ($request->components as $componentId => $data) {
                if ($data['value'] !== null && $data['value'] > 0) {
                    $calculationType = $data['type'] ?? 'fixed';
                    $originalValue = $data['value'];
                    $amount = $calculationType === 'percentage'
                        ? ($request->basic_salary * $originalValue / 100)
                        : $originalValue;

                    $componentsData[$componentId] = [
                        'amount' => $amount,
                        'calculation_type' => $calculationType,
                        'original_value' => $originalValue,
                    ];
                }
            }
            $salaryCard->components()->attach($componentsData);
        }

        $salaryCard->calculateTotals();

        $user = User::find($request->user_id);
        $user->salary_card_id = $salaryCard->id;
        $user->save();

        // Log history for creation
        $newValues = [
            'user_id' => $user->id,
            'basic_salary' => $salaryCard->basic_salary,
            'net_salary' => $salaryCard->net_salary,
            'total_earnings' => $salaryCard->total_earnings,
            'total_deductions' => $salaryCard->total_deductions,
            'components' => $componentsData,
        ];
        $this->logHistory($salaryCard, 'create', null, $newValues);

        return redirect()->route('salary-cards.index')->with('success', 'Salary card created successfully.');
    }

    public function show(SalaryCard $salaryCard)
    {
        // Eager load the user and components
        $salaryCard->load('user', 'components');
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
            'user_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'components' => 'array',
            'components.*.type' => 'nullable|in:fixed,percentage',
            'components.*.value' => 'nullable|numeric|min:0',
        ]);

        $oldValues = [
            'user_id' => $salaryCard->user ? $salaryCard->user->id : null,
            'basic_salary' => $salaryCard->basic_salary,
            'net_salary' => $salaryCard->net_salary,
            'total_earnings' => $salaryCard->total_earnings,
            'total_deductions' => $salaryCard->total_deductions,
            'components' => $salaryCard->components->pluck('pivot')->all(),
        ];

        $salaryCard->update([
            'basic_salary' => $request->basic_salary,
        ]);

        $componentsData = [];
        if ($request->has('components')) {
            foreach ($request->components as $componentId => $data) {
                if ($data['value'] !== null && $data['value'] > 0) {
                    $calculationType = $data['type'] ?? 'fixed';
                    $originalValue = $data['value'];
                    $amount = $calculationType === 'percentage'
                        ? ($request->basic_salary * $originalValue / 100)
                        : $originalValue;

                    $componentsData[$componentId] = [
                        'amount' => $amount,
                        'calculation_type' => $calculationType,
                        'original_value' => $originalValue,
                    ];
                }
            }
            $salaryCard->components()->sync($componentsData);
        } else {
            $salaryCard->components()->detach();
        }

        $salaryCard->calculateTotals();

        $user = User::find($request->user_id);
        if ($salaryCard->user && $salaryCard->user->id !== $user->id) {
            $oldUser = $salaryCard->user;
            $oldUser->salary_card_id = null;
            $oldUser->save();
        }
        $user->salary_card_id = $salaryCard->id;
        $user->save();

        // Log history for update
        $newValues = [
            'user_id' => $user->id,
            'basic_salary' => $salaryCard->basic_salary,
            'net_salary' => $salaryCard->net_salary,
            'total_earnings' => $salaryCard->total_earnings,
            'total_deductions' => $salaryCard->total_deductions,
            'components' => $componentsData,
        ];
        $this->logHistory($salaryCard, 'update', $oldValues, $newValues);

        return redirect()->route('salary-cards.index')->with('success', 'Salary card updated successfully.');
    }

    public function history(SalaryCard $salaryCard)
    {
        // Fetch history records with related user and changedBy data
        $histories = $salaryCard->histories()
            ->with(['user', 'changedBy'])
            ->orderBy('changed_at', 'desc')
            ->get();

        // Fetch all salary components to map IDs to names and types
        $components = SalaryComponent::all()->keyBy('id');

        return view('salary_cards.history', compact('salaryCard', 'histories', 'components'));
    }

    private function logHistory(SalaryCard $salaryCard, string $action, ?array $oldValues = null, array $newValues): void
    {
        SalaryCardHistory::create([
            'salary_card_id' => $salaryCard->id,
            'user_id' => $newValues['user_id'] ?? $salaryCard->user->id ?? null,
            'action' => $action,
            'old_values' => $oldValues, // Store raw array, not encoded string
            'new_values' => $newValues, // Store raw array, not encoded string
            'changed_at' => now(),
            'changed_by' => Auth::id(),
        ]);
    }
}
