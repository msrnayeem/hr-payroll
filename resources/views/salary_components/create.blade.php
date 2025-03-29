@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Components';
        $breadcrumbs = [['title' => 'Salary Components', 'url' => route('salarycomponent.index')]];
        $breadcrumbs[] = [
            'title' => 'New Salary Component',
            'url' => route('salarycomponent.create', ['type' => $type]),
        ];
    @endphp
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Create Salary Component</h3>
                <div class="card-tools">
                    <a href="{{ route('salarycomponent.index', ['type' => $type]) }}"
                        class="btn btn-sm btn-secondary">Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('salary-component.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Component Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="earning" @if ($type == 'earning') selected @endif>Earning</option>>
                            <option value="deduction" @if ($type == 'deduction') selected @endif>Deduction</option>
                        </select>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
