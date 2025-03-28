@extends('layouts.app')

@section('content')
    @php
        $type = request('type');
        $pageTitle = 'Salary Components';

        $breadcrumbs = [
            ['title' => 'Salary Components', 'url' => route('salarycomponent.index', ['type' => $type])],
            ['title' => 'Components', 'url' => route('salarycomponent.index', ['type' => $type])],
        ];
    @endphp
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Salary Components</h3>
                @can('create_salary_component')
                    <div class="card-tools">
                        <a href="{{ route('salary-component.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New
                        </a>
                    </div>
                @endcan
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover" id="permissionsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salaryComponents as $component)
                            <tr>
                                <td>{{ $component->id }}</td>
                                <td>{{ $component->name }}</td>
                                <td> {{ $component->type }}</td>
                                <td>{{ $component->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @can('edit_salary_component')
                                        <a href="{{ route('salary-component.edit', $component->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No records found</td>
                            </tr>
                        @endforelse
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
