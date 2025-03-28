@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Cards';
        $breadcrumbs = [['title' => 'Salary Cards', 'url' => route('salary-cards.index')]];
        $breadcrumbs[] = ['title' => 'Edit Salary Card', 'url' => route('salary-cards.edit', $salaryCard->id)];
    @endphp
    <div class="container">
        <h1>Edit Salary Card #{{ $salaryCard->id }}</h1>
        <form action="{{ route('salary-cards.update', $salaryCard->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- User Selection -->
            <div class="form-group mb-3">
                <label for="user_id">Select User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">-- Select User --</option>
                    @foreach (\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}"
                            {{ $salaryCard->user && $salaryCard->user->id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
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
                        <select name="components[{{ $component->id }}][type]" class="form-control">
                            <option value="fixed" {{ $calculationType === 'fixed' ? 'selected' : '' }}>Fixed Amount
                            </option>
                            <option value="percentage" {{ $calculationType === 'percentage' ? 'selected' : '' }}>% of Basic
                                Salary</option>
                        </select>
                        <input type="number" name="components[{{ $component->id }}][value]"
                            id="component_{{ $component->id }}" class="form-control" step="0.01"
                            value="{{ old("components.{$component->id}.value", $originalValue) }}"
                            placeholder="Enter value (optional)">
                    </div>
                </div>
            @endforeach

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Update Salary Card</button>
            <a href="{{ route('salary-cards.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
