<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shifts = Shift::all();

        return view('shifts.index', compact('shifts'));
    }


    public function store(Request $request)
    {

        $request->merge([
            'start_time' => $request->start_time ? (substr_count($request->start_time, ':') < 2 ? $request->start_time . ':00' : $request->start_time) : null,
            'end_time' => $request->end_time ? (substr_count($request->end_time, ':') < 2 ? $request->end_time . ':00' : $request->end_time) : null,
            'late_time' => $request->late_time ? (substr_count($request->late_time, ':') < 2 ? $request->late_time . ':00' : $request->late_time) : null,
            'early_time' => $request->early_time ? (substr_count($request->early_time, ':') < 2 ? $request->early_time . ':00' : $request->early_time) : null,
        ]);

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'start_time'  => 'required|date_format:H:i:s', // Update validation to match
            'end_time'    => 'required|date_format:H:i:s|after:start_time',
            'late_time'   => 'nullable|date_format:H:i:s|after:start_time',
            'early_time'  => 'nullable|date_format:H:i:s|before:end_time',
            'saturday'    => 'required|boolean',
            'sunday'      => 'required|boolean',
            'monday'      => 'required|boolean',
            'tuesday'     => 'required|boolean',
            'wednesday'   => 'required|boolean',
            'thursday'    => 'required|boolean',
            'friday'      => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Create new shift
        $shift = Shift::create([
            'name'        => $request->name,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'late_time'   => $request->late_time ?? '00:00:00',
            'early_time'  => $request->early_time ?? '00:00:00',
            'saturday'    => $request->boolean('saturday'),
            'sunday'      => $request->boolean('sunday'),
            'monday'      => $request->boolean('monday'),
            'tuesday'     => $request->boolean('tuesday'),
            'wednesday'   => $request->boolean('wednesday'),
            'thursday'    => $request->boolean('thursday'),
            'friday'      => $request->boolean('friday'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shift created successfully.',
            'data'    => $shift
        ]);
    }


    public function edit(Shift $shift)
    {
        return response()->json($shift);
    }

    public function update(Request $request, Shift $shift)
    {
        $request->merge([
            'start_time' => $request->start_time ? (substr_count($request->start_time, ':') < 2 ? $request->start_time . ':00' : $request->start_time) : null,
            'end_time' => $request->end_time ? (substr_count($request->end_time, ':') < 2 ? $request->end_time . ':00' : $request->end_time) : null,
            'late_time' => $request->late_time ? (substr_count($request->late_time, ':') < 2 ? $request->late_time . ':00' : $request->late_time) : null,
            'early_time' => $request->early_time ? (substr_count($request->early_time, ':') < 2 ? $request->early_time . ':00' : $request->early_time) : null,
        ]);

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'start_time'  => 'required|date_format:H:i:s',
            'end_time'    => 'required|date_format:H:i:s|after:start_time',
            'late_time'   => 'nullable|date_format:H:i:s|after:start_time',
            'early_time'  => 'nullable|date_format:H:i:s|before:end_time',
            'saturday'    => 'required|boolean',
            'sunday'      => 'required|boolean',
            'monday'      => 'required|boolean',
            'tuesday'     => 'required|boolean',
            'wednesday'   => 'required|boolean',
            'thursday'    => 'required|boolean',
            'friday'      => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $shift->update([
            'name'        => $request->name,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'late_time'   => $request->late_time ?? $request->start_time,
            'early_time'  => $request->early_time ?? $request->end_time,
            'saturday'    => $request->boolean('saturday'),
            'sunday'      => $request->boolean('sunday'),
            'monday'      => $request->boolean('monday'),
            'tuesday'     => $request->boolean('tuesday'),
            'wednesday'   => $request->boolean('wednesday'),
            'thursday'    => $request->boolean('thursday'),
            'friday'      => $request->boolean('friday'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shift updated successfully.',
            'data'    => $shift
        ]);
    }
}
