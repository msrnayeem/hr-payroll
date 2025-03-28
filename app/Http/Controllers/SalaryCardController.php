<?php

namespace App\Http\Controllers;

use App\Models\SalaryCard;
use Illuminate\Http\Request;

class SalaryCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('sal');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryCard $salaryCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryCard $salaryCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryCard $salaryCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryCard $salaryCard)
    {
        //
    }
}
