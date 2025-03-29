@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Components';
        $breadcrumbs = [
            [
                'title' => 'Salary Components',
                'url' => route('salarycomponent.index', ['type' => $salaryComponent->type]),
            ],
            ['title' => 'Edit Salary Component', 'url' => route('salary-component.edit', $salaryComponent->id)],
        ];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Salary Component</h3>
                <div class="card-tools">
                    <a href="{{ route('salarycomponent.index', ['type' => $salaryComponent->type]) }}"
                        class="btn btn-sm btn-secondary">Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('salary-component.update', $salaryComponent) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Component Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $salaryComponent->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="earning" {{ $salaryComponent->type == 'earning' ? 'selected' : '' }}>Earning
                            </option>
                            <option value="deduction" {{ $salaryComponent->type == 'deduction' ? 'selected' : '' }}>
                                Deduction
                            </option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('salarycomponent.index', ['type' => $salaryComponent->type]) }}"
                            class="btn btn-secondary">Cancel</a>
                    </div>


                </form>
            </div>
        </div>
    </div>
@endsection
