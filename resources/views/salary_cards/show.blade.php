@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Card Details';
        $breadcrumbs = [
            ['title' => 'Salary Cards', 'url' => route('salary-cards.index')],
            ['title' => "Salary Card #{$salaryCard->id}", 'url' => route('salary-cards.show', $salaryCard->id)],
        ];
    @endphp
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Salary Card #{{ $salaryCard->id }}</h3>

                <div class="card-tools">
                    <a href="{{ route('salary-cards.edit', $salaryCard->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                    <a href="{{ route('salary-cards.history', $salaryCard->id) }}"
                        class="btn btn-sm btn-secondary me-2">History</a>
                    <a href="{{ route('salary-cards.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>
            </div>
            <div class="card-body">
                <!-- Salary Card Details -->
                <div class="card mb-4">
                    <div class="card-header">Salary Card Details</div>
                    <div class="card-body">
                        <p><strong>User:</strong> {{ $salaryCard->user ? $salaryCard->user->name : 'Unassigned' }}</p>
                        <p><strong>Basic Salary:</strong> ৳{{ number_format($salaryCard->basic_salary, 2) }}</p>
                        <p><strong>Net Salary:</strong> ৳{{ number_format($salaryCard->net_salary, 2) }}</p>
                        <p><strong>Total Earnings:</strong> ৳{{ number_format($salaryCard->total_earnings, 2) }}</p>
                        <p><strong>Total Deductions:</strong> ৳{{ number_format($salaryCard->total_deductions, 2) }}</p>
                        <p><strong>Components:</strong>
                            @if ($salaryCard->components->isNotEmpty())
                                <ul>
                                    @foreach ($salaryCard->components as $component)
                                        <li>
                                            {{ $component->name }} ({{ ucfirst($component->type) }}):
                                            ৳{{ number_format($component->pivot->amount, 2) }}
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
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    ul {
        padding-left: 20px;
        margin: 0;
    }

    ul li {
        margin-bottom: 5px;
    }
</style>
