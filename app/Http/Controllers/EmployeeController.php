<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = User::with('shift')->get();
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shifts = Shift::all();
        return view('employees.create', compact('shifts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'shift_id' => 'nullable|exists:shifts,id',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create Employee
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'shift_id' => $request->shift_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $employee)
    {
        $shifts = Shift::all();
        return view('employees.edit', compact('employee', 'shifts'));
    }


    public function update(Request $request, User $employee)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'shift_id' => 'nullable|exists:shifts,id',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Update fields
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->shift_id = $request->shift_id;

        // Update password only if provided
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->save();

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }
}
