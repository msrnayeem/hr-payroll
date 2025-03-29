<?php

namespace App\Http\Controllers;

use App\Models\LeaveCategory;
use Illuminate\Http\Request;

class LeaveCategoryController extends Controller
{
    public function index()
    {
        $leave_categories = LeaveCategory::all();
        return view('leave_categories.index', compact('leave_categories'));
    }

    public function store(Request $request)
    {
        $leave_category = LeaveCategory::create($request->all());
        return response()->json($leave_category);
    }

    public function edit(LeaveCategory $leave_category)
    {
        return response()->json($leave_category);
    }

    public function update(Request $request, LeaveCategory $leave_category)
    {
        $leave_category->update($request->all());

        return response()->json($leave_category);
    }
}
