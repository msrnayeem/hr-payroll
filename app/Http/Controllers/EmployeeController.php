<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'shift_id' => 'nullable|exists:shifts,id',
            'password' => 'required|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prepare data for creation
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'shift_id' => $request->shift_id,
            'password' => Hash::make($request->password),
        ];

        // Create Employee first to get the ID
        $user = User::create($data);

        // Handle profile image upload after user creation
        if ($request->hasFile('profile_image')) {
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $fileName = $user->id . '.' . $extension; // Use the newly created user's ID
            $path = $request->file('profile_image')->storeAs('employee', $fileName, 'public');

            // Update the user with the profile image path
            $user->update(['profile_image' => $path]);
        }

        // Assign role to employee
        $user->assignRole('employee');

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $employee)
    {
        return view('employees.show', compact('employee'));
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
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prepare data for update
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'shift_id' => $request->shift_id,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if it exists
            if ($employee->profile_image && Storage::disk('public')->exists($employee->profile_image)) {
                Storage::disk('public')->delete($employee->profile_image);
            }

            // Generate new filename using employee ID
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $fileName = $employee->id . '.' . $extension;
            $path = $request->file('profile_image')->storeAs('employee', $fileName, 'public');
            $data['profile_image'] = $path;
        }

        // Update the employee
        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }
}
