@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'View Employee';
        $breadcrumbs = [
            ['title' => 'Employees', 'url' => route('employees.index')],
            ['title' => 'View Employee', 'url' => '#'],
        ];
    @endphp

    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Employee Details</h3>
                    <a href="{{ route('employees.index', ['status' => $employee->status]) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <img src="{{ $employee->profile_image ? Storage::url($employee->profile_image) : asset('assets/img/default-profile.jpg') }}"
                                class="rounded-circle shadow-sm img-fluid border border-2 border-primary"
                                style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Picture">
                            <h4 class="mt-3 fw-bold">{{ $employee->name }}</h4>
                            <p class="text-muted mb-0">{{ $employee->email }}</p>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Employee ID</label>
                            <p class="form-control-plaintext">{{ $employee->id }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <p class="form-control-plaintext">{{ $employee->name }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">
                                {{ $employee->is_active ? 'Active' : 'Inactive' }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <p class="form-control-plaintext">{{ $employee->email }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="form-control-plaintext">{{ $employee->phone }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Shift</label>
                            <p class="form-control-plaintext">
                                {{ $employee->shift ? $employee->shift->name : 'No shift assigned' }}
                            </p>
                        </div>

                        @can('edit_employee')
                            <div class="mt-4">
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary px-4">
                                    <i class="fas fa-edit"></i> Edit Employee
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
