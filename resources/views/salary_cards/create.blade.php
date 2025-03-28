@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Cards';
        $breadcrumbs = [['title' => 'Salary Cards', 'url' => route('salary-cards.index')]];
        $breadcrumbs[] = ['title' => 'New Salary Card', 'url' => route('salary-cards.create')];
    @endphp
    <div class="container">
        <h1>Create Salary Card</h1>
        <form action="{{ route('salary-cards.store') }}" method="POST">
            @csrf

            <!-- User Selection -->
            <div class="form-group mb-3">
                <label for="user_id">Select User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">-- Select User --</option>
                    @foreach (\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Basic Salary -->
            <div class="form-group mb-3">
                <label for="basic_salary">Basic Salary</label>
                <input type="number" name="basic_salary" id="basic_salary" class="form-control" step="0.01" required>
                @error('basic_salary')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Salary Components -->
            <h3>Salary Components</h3>
            @foreach (\App\Models\SalaryComponent::all() as $component)
                <div class="form-group mb-3">
                    <label>{{ $component->name }} ({{ ucfirst($component->type) }})</label>
                    <div class="input-group">
                        <select name="components[{{ $component->id }}][type]" class="form-control">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">% of Basic Salary</option>
                        </select>
                        <input type="number" name="components[{{ $component->id }}][value]"
                            id="component_{{ $component->id }}" class="form-control" step="0.01"
                            placeholder="Enter value (optional)">
                    </div>
                </div>
            @endforeach

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Create Salary Card</button>
            <a href="{{ route('salary-cards.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
