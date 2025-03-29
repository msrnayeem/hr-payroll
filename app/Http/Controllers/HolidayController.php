<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;


class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::all();
        return view('holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $holiday = Holiday::create($request->all());
        return response()->json($holiday);
    }

    public function edit(Holiday $holiday)
    {
        return response()->json($holiday);
    }

    public function update(Request $request, Holiday $holiday)
    {
        $holiday->update($request->all());
        return response()->json($holiday);
    }
}
