@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4" style="background-color: #f4f6f9;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-soft-primary text-primary d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 text-primary fw-semibold">
                            <i class="bi bi-pencil me-2"></i> Edit Role: {{ $role->name }}
                        </h5>
                        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Roles
                        </a>
                    </div>

                    <form action="{{ route('roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body bg-white">
                            <div class="mb-4">
                                <label for="name" class="form-label text-dark-emphasis">Role Name</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Enter role name"
                                    value="{{ old('name', $role->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="permissions" class="form-label text-dark-emphasis">Assign Permissions</label>
                                <select name="permissions[]" id="permissions" class="form-control" multiple size="10">
                                    @foreach ($permissions as $permission)
                                        <option value="{{ $permission->id }}"
                                            {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'selected' : '' }}>
                                            {{ $permission->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Hold Ctrl (Windows) or Cmd (Mac) to select multiple permissions
                                </small>
                            </div>

                            <div class="card-footer bg-light py-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i> Update Role
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Reuse styles from index page */
            :root {
                --soft-primary: #e6f0ff;
            }

            .btn-soft-primary {
                background-color: var(--soft-primary);
                color: #0d6efd;
                border: 1px solid transparent;
            }

            .form-control:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            #permissions {
                max-height: 300px;
                overflow-y: auto;
            }
        </style>
    @endpush
@endsection
