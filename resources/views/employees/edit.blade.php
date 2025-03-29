@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Edit Employee';
        $breadcrumbs = [
            ['title' => 'Employees', 'url' => route('employees.index')],
            ['title' => 'Edit Employee', 'url' => '#'],
        ];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Edit Employee</h3>
                <div class="card-tools">
                    <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-primary">
                        Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('employees.update', $employee) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required
                            value="{{ old('name', $employee->name) }}">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required
                            value="{{ old('email', $employee->email) }}">
                    </div>

                    <div class="mb-3">
                        <label for="shift_id" class="form-label">Shift</label>
                        <select name="shift_id" id="shift_id" class="form-control @error('shift_id') is-invalid @enderror">
                            <option value="">Select Shift</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}"
                                    {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>
                                    {{ $shift->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('shift_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="change_password_checkbox">
                        <label class="form-check-label" for="change_password_checkbox">Change Password</label>
                    </div>

                    <div id="password_fields" class="d-none">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror">
                            <small class="text-muted">Leave blank if you do not want to change the password.</small>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary">Update Employee</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkbox = document.getElementById('change_password_checkbox');
            const passwordFields = document.getElementById('password_fields');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    passwordFields.classList.remove('d-none');
                } else {
                    passwordFields.classList.add('d-none');
                }
            });
        });
    </script>
@endsection
