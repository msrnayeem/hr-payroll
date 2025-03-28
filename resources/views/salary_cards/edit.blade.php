@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Cards';
        $breadcrumbs = [['title' => 'Salary Cards', 'url' => route('salary-cards.index')]];
        $breadcrumbs[] = ['title' => 'Edit Salary Card', 'url' => route('salary-cards.edit', $salaryCard->id)];
    @endphp
    <div class="container">
        <h1>Edit Salary Card #{{ $salaryCard->id }}</h1>
        <div class="row">
            <!-- Form Column -->
            <div class="col-md-8">
                <form action="{{ route('salary-cards.update', $salaryCard->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- User Selection -->
                    <div class="form-group mb-3">
                        <label for="user_id">Select User</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="{{ $salaryCard->user->id }}">
                                {{ $salaryCard->user->name }} ({{ $salaryCard->user->email }})
                            </option>
                        </select>
                        @error('user_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Basic Salary -->
                    <div class="form-group mb-3">
                        <label for="basic_salary">Basic Salary</label>
                        <input type="number" name="basic_salary" id="basic_salary" class="form-control" step="0.01"
                            value="{{ old('basic_salary', $salaryCard->basic_salary) }}" required>
                        @error('basic_salary')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Salary Components -->
                    <h3>Salary Components</h3>
                    @foreach (\App\Models\SalaryComponent::all() as $component)
                        @php
                            $pivot = $salaryCard->components->find($component->id);
                            $calculationType = $pivot ? $pivot->pivot->calculation_type : 'fixed';
                            $originalValue = $pivot ? $pivot->pivot->original_value : '';
                        @endphp
                        <div class="form-group mb-3">
                            <label>{{ $component->name }} ({{ ucfirst($component->type) }})</label>
                            <div class="input-group">
                                <select name="components[{{ $component->id }}][type]" class="form-control component-type">
                                    <option value="fixed" {{ $calculationType === 'fixed' ? 'selected' : '' }}>Fixed
                                        Amount
                                    </option>
                                    <option value="percentage" {{ $calculationType === 'percentage' ? 'selected' : '' }}>%
                                        of Basic
                                        Salary</option>
                                </select>
                                <input type="number" name="components[{{ $component->id }}][value]"
                                    id="component_{{ $component->id }}" class="form-control component-value" step="0.01"
                                    value="{{ old("components.{$component->id}.value", $originalValue) }}"
                                    placeholder="Enter value (optional)">
                            </div>
                        </div>
                    @endforeach

                    <!-- Submit Button -->
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary">Update Salary</button>
                        <a href="{{ route('salary-cards.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>

            <!-- Sticky Calculation Column -->
            <div class="col-md-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h4>Salary Breakdown</h4>
                        <p>Basic Salary: ৳<span id="display_basic_salary">0.00</span></p>
                        <p>Total Earnings: ৳<span id="display_total_earnings">0.00</span></p>
                        <p>Total Deductions: ৳<span id="display_total_deductions">0.00</span></p>
                        <p>Net Salary: ৳<span id="display_net_salary">0.00</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Live Calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const basicSalaryInput = document.getElementById('basic_salary');
            const componentTypes = document.querySelectorAll('.component-type');
            const componentValues = document.querySelectorAll('.component-value');

            function calculateSalary() {
                let basicSalary = parseFloat(basicSalaryInput.value) || 0;
                let totalEarnings = 0;
                let totalDeductions = 0;

                componentValues.forEach((input, index) => {
                    const value = parseFloat(input.value) || 0;
                    const type = componentTypes[index].value;
                    const componentType = input.closest('.form-group').querySelector('label').textContent
                        .includes('Deduction') ?
                        'deduction' :
                        'earning';

                    let amount = 0;
                    if (type === 'fixed') {
                        amount = value;
                    } else if (type === 'percentage') {
                        amount = (value / 100) * basicSalary;
                    }

                    if (componentType === 'earning') {
                        totalEarnings += amount;
                    } else {
                        totalDeductions += amount;
                    }
                });

                const netSalary = basicSalary + totalEarnings - totalDeductions;

                // Update display
                document.getElementById('display_basic_salary').textContent = basicSalary.toFixed(2);
                document.getElementById('display_total_earnings').textContent = totalEarnings.toFixed(2);
                document.getElementById('display_total_deductions').textContent = totalDeductions.toFixed(2);
                document.getElementById('display_net_salary').textContent = netSalary.toFixed(2);
            }

            // Add event listeners
            basicSalaryInput.addEventListener('input', calculateSalary);
            componentTypes.forEach(select => select.addEventListener('change', calculateSalary));
            componentValues.forEach(input => input.addEventListener('input', calculateSalary));

            // Initial calculation
            calculateSalary();
        });
    </script>
@endsection
