@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Employees';
        $breadcrumbs = [
            ['title' => 'Employees', 'url' => route('employees.index')],
            ['title' => 'All Employees', 'url' => ''],
        ];
    @endphp


    <div class="container-fluid py-4">
        <div class="card card-primary card-outline">
            <div class="card-header py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Employees</h3>
                    @if ($status == 'active')
                        <div class="d-flex gap-2">
                            @can('add_employee')
                                <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> New Employee
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body p-4">
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item"
                                href="{{ route('employees.index', request()->query() + ['export' => 'pdf']) }}"
                                target="_blank">
                                <i class="fas fa-file-pdf"></i> View PDF
                            </a></li>
                        <li><a class="dropdown-item"
                                href="{{ route('employees.index', request()->query() + ['export' => 'pdf', 'download' => '1']) }}">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a></li>
                        <li><a class="dropdown-item"
                                href="{{ route('employees.index', request()->query() + ['export' => 'excel']) }}">
                                <i class="fas fa-file-excel"></i> Export as Excel
                            </a></li>

                    </ul>
                </div>
                @include('employees.table')
            </div>
        </div>
    </div>

    @push('js')
    @endpush
@endsection
