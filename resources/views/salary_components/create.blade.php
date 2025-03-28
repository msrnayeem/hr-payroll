@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Salary Components';
        $breadcrumbs = [['title' => 'Salary Components', 'url' => route('salarycomponent.index')]];
        $breadcrumbs[] = ['title' => 'New Salary Component', 'url' => route('salary-component.create')];
    @endphp
    <div class="container">
        <h1>Create Salary Component</h1>
        <form action="{{ route('salary-component.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Component Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-4">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="earning">Earning</option>
                    <option value="deduction">Deduction</option>
                </select>
            </div>

            <div class="mt-6">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>


        </form>
    </div>
@endsection
