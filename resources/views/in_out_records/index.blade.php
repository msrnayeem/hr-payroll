@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'In-Out Records';
        $breadcrumbs = [
            ['title' => 'In-Out Records', 'url' => route('in-out-records.index')],
            ['title' => 'All Records', 'url' => ''],
        ];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">In-Out Records</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item"
                                        href="{{ route('in-out-records.index', array_merge(request()->query(), ['export' => 'pdf'])) }}"
                                        target="_blank">
                                        <i class="fas fa-file-pdf"></i> View PDF
                                    </a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('in-out-records.index', array_merge(request()->query(), ['export' => 'pdf', 'download' => '1'])) }}">
                                        <i class="fas fa-file-pdf"></i> Download PDF
                                    </a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('in-out-records.index', array_merge(request()->query(), ['export' => 'excel'])) }}">
                                        <i class="fas fa-file-excel"></i> Export as Excel
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('in-out-records.index') }}" class="form" autocomplete="off">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <x-employee-select :all-employee="true" />
                        </div>

                        <div class="col-md-4">
                            <x-date-range />
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('in-out-records.index') }}" class="btn btn-default">
                                    <i class="fas fa-sync"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                @include('in_out_records.table')
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#date').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear',
                        format: 'YYYY-MM-DD'
                    }
                });

                $('#date').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                });

                $('#date').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                });
            });
        </script>
    @endpush
@endsection
