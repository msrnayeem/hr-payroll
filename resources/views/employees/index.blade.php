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
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Shift Name</th>
                            <th>Salary Card</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->id }}</td>
                                <td>
                                    @if ($employee->profile_image)
                                        <img src="{{ Storage::url($employee->profile_image) }}" alt="Employee Image"
                                            width="50" height="50" class="img-thumbnail">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->shift->name ?? 'N\A' }}</td>
                                <td>
                                    @if ($employee->salary_card_id)
                                        <a href="{{ route('salary-cards.show', $employee->salary_card_id) }}">
                                            View
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @can('edit_employee')
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endcan
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
