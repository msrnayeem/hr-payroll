@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Cards';
        $breadcrumbs = [['title' => 'Salary Cards', 'url' => route('salary-cards.index')]];
    @endphp
    <div class="container">
        <h1>Salary Cards</h1>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Create Button -->
        <div class="mb-3">
            <a href="{{ route('salary-cards.create') }}" class="btn btn-primary">Create New Salary Card</a>
        </div>

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
                    <th>Components</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($salaryCards as $salaryCard)
                    <tr>
                        <td>{{ $salaryCard->id }}</td>
                        <td>{{ $salaryCard->user ? $salaryCard->user->name : 'Unassigned' }}</td>
                        <td>${{ number_format($salaryCard->basic_salary, 2) }}</td>
                        <td>${{ number_format($salaryCard->total_earnings, 2) }}</td>
                        <td>${{ number_format($salaryCard->total_deductions, 2) }}</td>
                        <td>${{ number_format($salaryCard->net_salary, 2) }}</td>
                        <td>
                            <!-- Show components in a simple list -->
                            @if ($salaryCard->components->isNotEmpty())
                                <ul>
                                    @foreach ($salaryCard->components as $component)
                                        <li>
                                            {{ $component->name }} ({{ ucfirst($component->type) }}):
                                            ${{ number_format($component->pivot->amount, 2) }}
                                            @if ($component->pivot->calculation_type === 'percentage')
                                                ({{ $component->pivot->original_value }}% of Basic)
                                            @else
                                                (Fixed)
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                No components
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('salary-cards.show', $salaryCard->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('salary-cards.edit', $salaryCard->id) }}"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('salary-cards.destroy', $salaryCard->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this salary card?')">
                                    Delete
                                </button>
                            </form>
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
@endsection
