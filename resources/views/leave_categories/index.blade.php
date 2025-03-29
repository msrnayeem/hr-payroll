@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Leave Category';
        $breadcrumbs = [['title' => 'Leave Categories', 'url' => route('leave-categories.index')]];
        $breadcrumbs[] = ['title' => 'All Categories'];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Categories List</h3>
                @can('add_leave_categories')
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                            <i class="fas fa-plus"></i> New Category
                        </button>
                    </div>
                @endcan
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover" id="leaveCategoryTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Maximum Days</th>
                            <th>Requires Approval ?</th>
                            @can('edit_leave_categories')
                                <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leave_categories as $leave_category)
                            <tr id="categoryRow-{{ $leave_category->id }}">
                                <td>{{ $leave_category->name }}</td>
                                <td>{{ $leave_category->max_days }}</td>
                                <td>{{ $leave_category->requires_approval ? 'Yes' : 'No' }}</td>

                                @can('edit_leave_categories')
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="openEditModal({{ $leave_category->id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('js')
        <!-- Include SweetAlert2 & jQuery -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            function openCreateModal() {
                Swal.fire({
                    title: 'Add New Category',
                    width: '600px',
                    html: `
            <div class="container">
                <form id="categoryForm" class="swal2-form">
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 text-left">
                            <label for="name" class="form-label mb-0">Category Name</label>
                        </div>
                        <div class="col-8">
                            <input id="name" class="form-control w-100" placeholder="Enter Name">
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 text-left">
                            <label for="max_days" class="form-label mb-0">Maximum Days</label>
                        </div>
                        <div class="col-8">
                            <input type="number" id="max_days" class="form-control w-100">
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 text-left">
                            <label for="requires_approval" class="form-label mb-0">Require Approval ?</label>
                        </div>
                        <div class="col-8">
                            <select id="requires_approval" class="form-control w-100">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        `,
                    showCancelButton: true,
                    confirmButtonText: 'Save',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        container: 'container-fluid',
                        confirmButton: 'btn btn-primary me-2',
                        cancelButton: 'btn btn-secondary me-2'
                    },
                    buttonsStyling: false,
                    focusConfirm: false,
                    preConfirm: () => {
                        let name = $('#name').val().trim();
                        let max_days = $('#max_days').val();
                        let requires_approval = $('#requires_approval').val();

                        // Basic validation
                        if (!name) {
                            Swal.showValidationMessage('Name is required');
                            return false;
                        }
                        if (!max_days) {
                            Swal.showValidationMessage('Maximum days is required');
                            return false;
                        }
                        if (!requires_approval) {
                            Swal.showValidationMessage('Requires approval is required');
                            return false;
                        }
                        // Submit form
                        return $.ajax({
                                url: '{{ route('leave-categories.store') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    name: name,
                                    max_days: max_days,
                                    requires_approval: requires_approval
                                }
                            })
                            .then(response => {
                                return response;
                            })
                            .catch(error => {
                                Swal.showValidationMessage(
                                    `Request failed: ${error.responseJSON?.message || 'Unknown error'}`);
                                return false;
                            });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Category added successfully.',
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            }

            function openEditModal(id) {
                $.ajax({
                    url: `/leave-categories/${id}/edit`,
                    method: 'GET',
                    success: function(response) {
                        Swal.fire({
                            title: 'Edit Category',
                            width: '600px',
                            html: `
                    <div class="container">
                        <form id="editcategoryForm" class="swal2-form">
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-left">
                                    <label for="edit_name" class="form-label mb-0">Name</label>
                                </div>
                                <div class="col-8">
                                    <input id="edit_name" class="form-control w-100" value="${response.name}">
                                </div>
                            </div>
                            
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-left">
                                    <label for="edit_max_days" class="form-label mb-0">Maximum Days</label>
                                </div>
                                <div class="col-8">
                                    <input type="number" id="edit_max_days" class="form-control w-100" value="${response.max_days}">
                                </div>
                            </div>
                            
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-left">
                                    <label for="edit_requires_approval" class="form-label mb-0">Status</label>
                                </div>
                                <div class="col-8">
                                    <select id="edit_requires_approval" class="form-control w-100">
                                        <option value="1" ${response.requires_approval == '1' ? 'selected' : ''}>Yes</option>
                                        <option value="0" ${response.requires_approval == '0' ? 'selected' : ''}>No</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                `,
                            showCancelButton: true,
                            confirmButtonText: 'Update',
                            cancelButtonText: 'Cancel',
                            customClass: {
                                container: 'container-fluid',
                                confirmButton: 'btn btn-primary me-2',
                                cancelButton: 'btn btn-secondary me-2'
                            },
                            buttonsStyling: false,
                            focusConfirm: false,
                            preConfirm: () => {
                                let name = $('#edit_name').val().trim();
                                let max_days = $('#edit_max_days').val();
                                let requires_approval = $('#edit_requires_approval').val();

                                // Basic validation
                                if (!name) {
                                    Swal.showValidationMessage('Name is required');
                                    return false;
                                }

                                if (!max_days) {
                                    Swal.showValidationMessage('Maximum days is required');
                                    return false;
                                }
                                if (!requires_approval) {
                                    Swal.showValidationMessage('Requires approval is required');
                                    return false;
                                }

                                // Submit form
                                return $.ajax({
                                        url: `/leave-categories/${id}`,
                                        method: 'PUT',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        data: {
                                            name: name,
                                            max_days: max_days,
                                            requires_approval: requires_approval
                                        }
                                    })
                                    .then(response => {
                                        return response;
                                    })
                                    .catch(error => {
                                        Swal.showValidationMessage(
                                            `Request failed: ${error.responseJSON?.message || 'Unknown error'}`
                                        );
                                        return false;
                                    });
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Category updated successfully.',
                                    icon: 'success',
                                    customClass: {
                                        confirmButton: 'btn btn-primary'
                                    },
                                    buttonsStyling: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to load category data.',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        });
                    }
                });
            }
        </script>
    @endpush
@endsection
