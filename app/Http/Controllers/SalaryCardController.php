<?php

namespace App\Http\Controllers;

use App\Models\SalaryCard;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalaryComponent;
use Illuminate\Support\Facades\Auth;

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
        return view('salary_cards.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'components' => 'array',
            'components.*.type' => 'nullable|in:fixed,percentage',
            'components.*.value' => 'nullable|numeric|min:0',
        ]);

        // Create the salary card
        $salaryCard = SalaryCard::create([
            'basic_salary' => $request->basic_salary,
            'net_salary' => 0, // Will be calculated
            'total_deductions' => 0, // Will be calculated
            'total_earnings' => 0, // Will be calculated
        ]);

        // Attach salary components with full information
        if ($request->has('components')) {
            foreach ($request->components as $componentId => $data) {
                if ($data['value'] !== null && $data['value'] > 0) {
                    $calculationType = $data['type'] ?? 'fixed';
                    $originalValue = $data['value'];

                    // Calculate the actual amount
                    $amount = $calculationType === 'percentage'
                        ? ($request->basic_salary * $originalValue / 100)
                        : $originalValue;

                    $salaryCard->components()->attach($componentId, [
                        'amount' => $amount,
                        'calculation_type' => $calculationType,
                        'original_value' => $originalValue,
                    ]);
                }
            }
        }

        // Calculate totals and update the salary card
        $salaryCard->calculateTotals();

        // Assign the salary card to the user
        $user = User::find($request->user_id);
        $user->salary_card_id = $salaryCard->id;
        $user->save();

        // Redirect with success message
        return redirect()->route('salary-cards.index')->with('success', 'Salary card created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryCard $salaryCard)
    {
        //
    }

    public function edit(SalaryCard $salaryCard)
    {
        return view('salary_cards.edit', compact('salaryCard'));
    }

    public function update(Request $request, SalaryCard $salaryCard)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'components' => 'array',
            'components.*.type' => 'nullable|in:fixed,percentage',
            'components.*.value' => 'nullable|numeric|min:0',
        ]);

        // Update the salary card’s basic salary
        $salaryCard->update([
            'basic_salary' => $request->basic_salary,
        ]);

        // Sync salary components with full information
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
        }

        // Sync components (replaces existing with new data)
        $salaryCard->components()->sync($componentsData);

        // Recalculate totals and update the salary card
        $salaryCard->calculateTotals();

        // Update the user assignment (if changed)
        $user = User::find($request->user_id);
        if ($salaryCard->user && $salaryCard->user->id !== $user->id) {
            $oldUser = $salaryCard->user;
            $oldUser->salary_card_id = null; // Unassign from old user
            $oldUser->save();
        }
        $user->salary_card_id = $salaryCard->id;
        $user->save();

        // Redirect with success message
        return redirect()->route('salary-cards.index')->with('success', 'Salary card updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryCard $salaryCard)
    {
        //
    }
}
