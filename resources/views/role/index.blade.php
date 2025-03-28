@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Roles';
        $breadcrumbs = [['title' => 'Roles', 'url' => route('roles.index')]];
        $breadcrumbs[] = ['title' => 'List', 'url' => route('roles.index')];
    @endphp
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Roles List</h3>
                @can('create_role')
                    <div class="card-tools">
                        <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New Role
                        </a>
                    </div>
                @endcan
            </div>

            <div class="card-body">
                <div class="row">
                    @foreach ($roles as $role)
                        <div class="col-md-4 mb-4">
                            <div class="card card-primary card-outline h-100">
                                <div class="card-header">
                                    <h5 class="card-title">{{ ucfirst($role->name) }} Role</h5>
                                    <div class="card-tools">
                                        <div class="btn-group">
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">Assigned Permissions</h6>
                                    <div class="permissions-container">
                                        @forelse ($role->permissions as $permission)
                                            <span class="badge bg-info me-1 mb-1">
                                                <i class="bi bi-check-circle me-1"></i>
                                                {{ $permission->name }}
                                            </span>
                                        @empty
                                            <div class="alert alert-warning py-2 px-3" role="alert">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                No permissions assigned
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">Total Permissions: {{ $role->permissions->count() }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            .permissions-container {
                max-height: 200px;
                overflow-y: auto;
            }

            .badge {
                font-size: 0.7rem;
                padding: 0.3em 0.5em;
            }
        </style>
    @endpush

    @push('js')
    @endpush
@endsection
