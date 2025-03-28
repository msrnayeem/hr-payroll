@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Permissions';
        $breadcrumbs = [['title' => 'Permissions', 'url' => route('permissions.index')]];
        $breadcrumbs[] = ['title' => 'All Permissions', 'url' => route('permissions.index')];
    @endphp
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Permissions List</h3>
                @can('create_permission')
                    <div class="card-tools">
                        <a href="{{ route('permissions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New Permission
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
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->id }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('permissions.edit', $permission->id) }}"
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
        <link rel="stylesheet" href="/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="/vendor/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    @endpush

    @push('js')
        <script src="/vendor/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="/vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.js"></script>

        <script>
            $(function() {
                $('#permissionsTable').DataTable({
                    "responsive": true,
                    "autoWidth": false,
                });

                $('.delete-permission').on('click', function() {
                    const permissionId = $(this).data('id');
                    const permissionName = $(this).data('name');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to delete the permission "${permissionName}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `{{ url('permissions') }}/${permissionId}`,
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    Swal.fire(
                                        'Deleted!',
                                        'Permission has been deleted.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function(xhr) {
                                    Swal.fire(
                                        'Error!',
                                        'Could not delete the permission.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
