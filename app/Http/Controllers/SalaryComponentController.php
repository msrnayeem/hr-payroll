<?php

namespace App\Http\Controllers;

use App\Models\SalaryComponent;
use Illuminate\Http\Request;

class SalaryComponentController extends Controller
{
    public function index(Request $request, $type = null)
    {
        // Validate and ensure type is either 'earning' or 'deduction'
        if (!in_array($type, ['earning', 'deduction', null])) {
            abort(404); // Handle invalid types
        }

        // Fetch salary components based on type
        $salaryComponents = SalaryComponent::when($type, function ($query) use ($type) {
            return $query->where('type', $type);
        })->get();

        return view('salary_components.index', compact('salaryComponents', 'type'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $type = null)
    {
        if (!in_array($type, ['earning', 'deduction', null])) {
            abort(404); // Handle invalid types
        }
        return view('salary_components.create', compact('type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:salary_components,name',
            'type' => 'required|in:earning,deduction',
        ]);

        SalaryComponent::create($request->only('name', 'type'));

        return redirect()->route('salarycomponent.index', ['type' => $request->type])->with('success', 'Salary Component created successfully.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryComponent $salaryComponent)
    {
        return view('salary_components.edit', compact('salaryComponent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryComponent $salaryComponent)
    {
        $request->validate([
            'name' => 'required|string|unique:salary_components,name,' . $salaryComponent->id,
            'type' => 'required|in:earning,deduction',
        ]);

        $salaryComponent->update($request->only('name', 'type'));

        return redirect()->route('salarycomponent.index', ['type' => $request->type])->with('success', 'Salary Component updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryComponent $salaryComponent)
    {
        //
    }
}
