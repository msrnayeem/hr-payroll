@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Edit Employee';
        $breadcrumbs = [
            ['title' => 'Employees', 'url' => route('employees.index')],
            ['title' => 'Edit Employee', 'url' => '#'],
        ];
    @endphp

    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Edit Employee</h3>
                    <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Profile Image Section -->
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img src="{{ $employee->profile_image ? Storage::url($employee->profile_image) : asset('assets/img/default-profile.jpg') }}"
                                        class="rounded-circle shadow-sm img-fluid border border-2 border-primary"
                                        style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Preview"
                                        id="profile-preview">
                                </div>
                                <div class="mt-3">
                                    <label for="profile_image" class="form-label fw-bold">Profile Image</label>
                                    <input type="file" name="profile_image" id="profile_image"
                                        class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                                    <small class="text-muted">Max 2MB, JPG/PNG only</small>
                                    @error('profile_image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $employee->name) }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $employee->email) }}" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="shift_id" class="form-label fw-bold">Shift</label>
                                <select name="shift_id" id="shift_id"
                                    class="form-select @error('shift_id') is-invalid @enderror">
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
                                <label class="form-check-label fw-bold" for="change_password_checkbox">Change
                                    Password</label>
                            </div>

                            <div id="password_fields" class="d-none">
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-bold">New Password</label>
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    <small class="text-muted">Minimum 6 characters, leave blank to keep current</small>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror">
                                    @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save"></i> Update Employee
                                </button>
                                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checkbox = document.getElementById('change_password_checkbox');
            const passwordFields = document.getElementById('password_fields');
            const profileImage = document.getElementById('profile_image');
            const profilePreview = document.getElementById('profile-preview');

            checkbox.addEventListener('change', function() {
                passwordFields.classList.toggle('d-none', !this.checked);
            });

            profileImage.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
