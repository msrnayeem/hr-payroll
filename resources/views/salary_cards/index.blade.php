@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Cards';
        $breadcrumbs = [['title' => 'Salary Cards', 'url' => route('salary-cards.index')]];
    @endphp
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Salary Cards</h3>
                @can('add_salary_card')
                    <div class="card-tools">
                        <a href="{{ route('salary-cards.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>New Salary Card</a>
                    </div>
                @endcan
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card-body">
                <!-- Salary Cards Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Basic Salary</th>
                            <th>Total Earnings</th>
                            <th>Total Deductions</th>
                            <th>Net Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salaryCards as $salaryCard)
                            <tr>
                                <td>{{ $salaryCard->id }}</td>
                                <td>{{ $salaryCard->user ? $salaryCard->user->name : 'Unassigned' }}</td>
                                <td>৳{{ number_format($salaryCard->basic_salary, 2) }}</td>
                                <td>৳{{ number_format($salaryCard->total_earnings, 2) }}</td>
                                <td>৳{{ number_format($salaryCard->total_deductions, 2) }}</td>
                                <td>৳{{ number_format($salaryCard->net_salary, 2) }}</td>

                                <td>
                                    <a href="{{ route('salary-cards.show', $salaryCard->id) }}"
                                        class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('salary-cards.edit', $salaryCard->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ route('salary-cards.history', $salaryCard->id) }}"
                                        class="btn btn-secondary btn-sm">History</a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No salary cards found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
