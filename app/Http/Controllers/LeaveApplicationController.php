<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveApplicationController extends Controller
{
    public function index()
    {
        $leaveApplications = LeaveApplication::all();
        $leaveCategories = LeaveCategory::all();
        $users = User::all();
        return view('leave_applications.index', compact('leaveApplications', 'leaveCategories', 'users'));
    }


    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_category_id' => 'required|exists:leave_categories,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
            'reason' => 'nullable|string',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        // Calculate the total days based on the from_date and to_date
        $fromDate = Carbon::parse($validated['from_date']);
        $toDate = Carbon::parse($validated['to_date']);
        $totalDays = $fromDate->diffInDays($toDate) + 1;

        // Handle file upload if necessary
        $supportingDocument = null;
        if ($request->hasFile('supporting_document')) {
            $supportingDocument = $request->file('supporting_document')->store('supporting_documents');
        }

        // Create the leave application
        $leaveApplication = LeaveApplication::create([
            'user_id' => $validated['user_id'],
            'leave_category_id' => $validated['leave_category_id'],
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'total_days' => $totalDays,
            'status' => $validated['status'],
            'reason' => $validated['reason'],
            'supporting_document' => $supportingDocument,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Leave application submitted successfully!',
            'data' => $leaveApplication,
        ]);
    }


    public function edit($id)
    {
        $leaveApplication = LeaveApplication::find($id);
        return response()->json($leaveApplication);
    }

    public function update(Request $request, $id)
    {
        $leaveApplication = LeaveApplication::findOrFail($id);

        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected',
            'reason' => 'nullable|string',
            'supporting_document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        // Calculate the total days
        $fromDate = Carbon::parse($validated['from_date']);
        $toDate = Carbon::parse($validated['to_date']);
        $totalDays = $fromDate->diffInDays($toDate) + 1;

        // Handle the supporting document upload
        if ($request->hasFile('supporting_document')) {
            $supportingDocument = $request->file('supporting_document')->store('supporting_documents');
            $validated['supporting_document'] = $supportingDocument;
        }

        // Update the leave application
        $leaveApplication->update([
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'total_days' => $totalDays,
            'status' => $validated['status'],
            'reason' => $validated['reason'],
            'supporting_document' => $validated['supporting_document'] ?? $leaveApplication->supporting_document,
        ]);

        return response()->json(['message' => 'Leave application updated successfully!', 'data' => $leaveApplication]);
    }
}
