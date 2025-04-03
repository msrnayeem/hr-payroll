<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'active');

        $query = User::with('shift');

        // Apply status filter
        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        $employees = $query->get();

        // Export logic
        if ($request->has('export')) {
            $exportType = $request->export;

            if ($exportType === 'pdf') {
                $export = true;
                $pdf = PDF::loadView('employees.table', compact('employees', 'export'));
                if ($request->has('download')) {
                    return $pdf->download('employees-list.pdf');
                }
                return $pdf->stream('employees-list.pdf');
            } elseif ($exportType === 'excel') {
                return Excel::download(new EmployeesExport($employees), 'employees-list.xlsx');
            }
        }

        return view('employees.index', compact('employees', 'status'));
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
            'phone' => 'nullable|string|max:15',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Prepare data for creation
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'shift_id' => $request->shift_id,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'status' => $request->status ?? 'active',
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

        return redirect()->route('employees.index', ['status' => 'active'])->with('success', 'Employee created successfully.');
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
            'phone' => 'nullable|string|max:15',
            'status' => 'nullable|in:active,inactive',
        ]);

        // Prepare data for update
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'shift_id' => $request->shift_id,
            'phone' => $request->phone,
            'status' => $request->status ?? 'active',
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

        return redirect()->route('employees.index', ['status' => 'active'])->with('success', 'Employee updated successfully.');
    }

    public function updateStatus(Request $request, User $employee)
    {
        // Check if the authenticated user is trying to update their own status
        if ($employee->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot update your own status.');
        }

        // Validate the status input
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        // Update the status of the employee
        $employee->status = $request->status;
        $employee->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Employee status updated successfully.');
    }
}
