@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Employees';
        $breadcrumbs = [['title' => 'Employees', 'url' => route('employees.index')]];
        $breadcrumbs[] = ['title' => 'All Employees', 'url' => route('employees.index')];
    @endphp
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Employees</h3>
                @can('create_permission')
                    <div class="card-tools">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Employee
                        </a>
                    </div>
                @endcan
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover" id="employeesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Shift Name</th>
                            <th>Salary Card ID</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->shift->name ?? 'N\A' }}</td>
                                <td>{{ $employee->salary_card_id }}</td>
                                <td>{{ $employee->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('css')
    @endpush

    @push('js')
    @endpush
@endsection
