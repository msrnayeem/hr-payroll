@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Card History';
        $breadcrumbs = [
            ['title' => 'Salary Cards', 'url' => route('salary-cards.index')],
            ['title' => "Salary Card #{$salaryCard->id}", 'url' => route('salary-cards.show', $salaryCard->id)],
            ['title' => 'History', 'url' => route('salary-cards.history', $salaryCard->id)],
        ];
    @endphp
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">History for Salary Card #{{ $salaryCard->id }}</h3>
                <div class="card-tools">
                    <a href="{{ route('salary-cards.index') }}" class="btn btn-secondary btn-sm">Back</a>
                </div>

            </div>
            <div class="card-body">

                <!-- Salary Card Summary -->
                <div class="card mb-4">
                    <div class="card-header">Current Salary Card Details</div>
                    <div class="card-body">
                        <p><strong>User:</strong> {{ $salaryCard->user ? $salaryCard->user->name : 'Unassigned' }}</p>
                        <p><strong>Basic Salary:</strong> ${{ number_format($salaryCard->basic_salary, 2) }}</p>
                        <p><strong>Net Salary:</strong> ${{ number_format($salaryCard->net_salary, 2) }}</p>
                        <p><strong>Total Earnings:</strong> ${{ number_format($salaryCard->total_earnings, 2) }}</p>
                        <p><strong>Total Deductions:</strong> ${{ number_format($salaryCard->total_deductions, 2) }}</p>
                    </div>
                </div>

                <!-- History Logs Table -->
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Action</th>
                            <th>User</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                            <th>Changed At</th>
                            <th>Changed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td>{{ ucfirst($history->action) }}</td>
                                <td>{{ $history->user ? $history->user->name : 'N/A' }}</td>
                                <td>
                                    @if ($history->old_values)
                                        <ul>
                                            <li><strong>User ID:</strong> {{ $history->old_values['user_id'] ?? 'N/A' }}
                                            </li>
                                            <li><strong>Basic Salary:</strong>
                                                ${{ number_format($history->old_values['basic_salary'], 2) }}</li>
                                            <li><strong>Net Salary:</strong>
                                                ${{ number_format($history->old_values['net_salary'], 2) }}</li>
                                            <li><strong>Total Earnings:</strong>
                                                ${{ number_format($history->old_values['total_earnings'], 2) }}</li>
                                            <li><strong>Total Deductions:</strong>
                                                ${{ number_format($history->old_values['total_deductions'], 2) }}</li>
                                            <li><strong>Components:</strong>
                                                @if (!empty($history->old_values['components']))
                                                    <ul>
                                                        @foreach ($history->old_values['components'] as $componentId => $details)
                                                            @php
                                                                $component = $components->get($componentId);
                                                            @endphp
                                                            @if ($component)
                                                                <li>
                                                                    {{ $component->name }}
                                                                    ({{ ucfirst($component->type) }})
                                                                    :
                                                                    ${{ number_format($details['amount'], 2) }}
                                                                    @if ($details['calculation_type'] === 'percentage')
                                                                        ({{ $details['original_value'] }}% of Basic)
                                                                    @else
                                                                        (Fixed)
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    No components
                                                @endif
                                            </li>
                                        </ul>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <ul>
                                        <li><strong>User ID:</strong> {{ $history->new_values['user_id'] ?? 'N/A' }}</li>
                                        <li><strong>Basic Salary:</strong>
                                            ${{ number_format($history->new_values['basic_salary'], 2) }}</li>
                                        <li><strong>Net Salary:</strong>
                                            ${{ number_format($history->new_values['net_salary'], 2) }}</li>
                                        <li><strong>Total Earnings:</strong>
                                            ${{ number_format($history->new_values['total_earnings'], 2) }}</li>
                                        <li><strong>Total Deductions:</strong>
                                            ${{ number_format($history->new_values['total_deductions'], 2) }}</li>
                                        <li><strong>Components:</strong>
                                            @if (!empty($history->new_values['components']))
                                                <ul>
                                                    @foreach ($history->new_values['components'] as $componentId => $details)
                                                        @php
                                                            $component = $components->get($componentId);
                                                        @endphp
                                                        @if ($component)
                                                            <li>
                                                                {{ $component->name }} ({{ ucfirst($component->type) }}):
                                                                ${{ number_format($details['amount'], 2) }}
                                                                @if ($details['calculation_type'] === 'percentage')
                                                                    ({{ $details['original_value'] }}% of Basic)
                                                                @else
                                                                    (Fixed)
                                                                @endif
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @else
                                                No components
                                            @endif
                                        </li>
                                    </ul>
                                </td>
                                <td>{{ $history->changed_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $history->changedBy ? $history->changedBy->name : 'System' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No history records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
