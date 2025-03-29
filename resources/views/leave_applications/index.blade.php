@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Leave Applications';
        $breadcrumbs = [['title' => 'Leave Applications', 'url' => route('leave-applications.index')]];
        $breadcrumbs[] = ['title' => 'All Leave Applications'];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Leave Applications</h3>
                @can('add_leave_applications')
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" id="createLeaveApplicationBtn">
                            <i class="fas fa-plus"></i> New Leave Application
                        </button>
                    </div>
                @endcan
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover" id="leaveApplicationsTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Leave Type</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Total Days</th>
                            <th>Supporting Document</th>
                            <th>Status</th>
                            @can('edit_leave_applications')
                                <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveApplications as $leaveApplication)
                            <tr id="leaveApplicationRow-{{ $leaveApplication->id }}">
                                <td>{{ $leaveApplication->user->name }}</td>
                                <td>{{ $leaveApplication->leaveCategory->name }}</td>
                                <td>{{ $leaveApplication->from_date }}</td>
                                <td>{{ $leaveApplication->to_date }}</td>
                                <td>
                                    {{ $leaveApplication->total_days }}
                                </td>
                                <td>
                                    @if ($leaveApplication->supporting_document)
                                        <a href="{{ asset('storage/' . $leaveApplication->supporting_document) }}"
                                            target="_blank">View Document</a>
                                    @else
                                        No Document
                                    @endif
                                </td>
                                <td>
                                    {{ ucfirst($leaveApplication->status) }}
                                </td>
                                @can('edit_leave_applications')
                                    <td>
                                        <button class="btn btn-warning btn-sm editLeaveApplicationBtn"
                                            data-id="{{ $leaveApplication->id }}">
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
            // Wait for the document to be fully loaded
            $(document).ready(function() {

                // Function to open the create leave modal
                $('#createLeaveApplicationBtn').click(function() {
                    openCreateModal();
                });

                $('.editLeaveApplicationBtn').click(function() {
                    console.log('Edit button clicked');

                    // Get the data-id attribute value
                    const leaveApplicationId = $(this).data('id');

                    // Call the openEditModal function and pass the ID
                    openEditModal(leaveApplicationId);
                });

                function openCreateModal() {
                    Swal.fire({
                        title: 'Apply for Leave',
                        width: '600px',
                        html: `
                            <div class="container">
                                <form id="leaveApplicationForm" class="swal2-form">
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="user_id" class="form-label mb-0">Employee Name</label>
                                        </div>
                                        <div class="col-8">
                                            <select id="user_id" class="form-control w-100">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="leave_category_id" class="form-label mb-0">Leave Type</label>
                                        </div>
                                        <div class="col-8">
                                            <select id="leave_category_id" class="form-control w-100">
                                                @foreach ($leaveCategories as $leaveCategory)
                                                    <option value="{{ $leaveCategory->id }}">{{ $leaveCategory->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="from_date" class="form-label mb-0">From Date</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="date" id="from_date" class="form-control w-100">
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="to_date" class="form-label mb-0">To Date</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="date" id="to_date" class="form-control w-100">
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="status" class="form-label mb-0">Status</label>
                                        </div>
                                        <div class="col-8">
                                            <select id="status" class="form-control w-100">
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="reason" class="form-label mb-0">Reason</label>
                                        </div>
                                        <div class="col-8">
                                            <textarea id="reason" class="form-control w-100" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="supporting_document" class="form-label mb-0">Supporting Document</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="file" id="supporting_document" class="form-control w-100">
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
                            let user_id = $('#user_id').val();
                            let leave_category_id = $('#leave_category_id').val();
                            let from_date = $('#from_date').val();
                            let to_date = $('#to_date').val();
                            let status = $('#status').val();
                            let reason = $('#reason').val();
                            let supporting_document = $('#supporting_document')[0].files[0];

                            // Validation
                            if (!user_id || !leave_category_id || !from_date || !to_date || !status) {
                                Swal.showValidationMessage('All fields are required');
                                return false;
                            }

                            // Submit form
                            const formData = new FormData();
                            formData.append('user_id', user_id);
                            formData.append('leave_category_id', leave_category_id);
                            formData.append('from_date', from_date);
                            formData.append('to_date', to_date);
                            formData.append('status', status);
                            formData.append('reason', reason);
                            if (supporting_document) {
                                formData.append('supporting_document', supporting_document);
                            }

                            return $.ajax({
                                url: '{{ route('leave-applications.store') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: formData,
                                processData: false,
                                contentType: false,
                            }).then(response => {
                                return response;
                            }).catch(error => {
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
                                text: 'Leave application submitted successfully.',
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
                    // Make an AJAX request to get the data for the leave application by ID
                    $.ajax({
                        url: `/leave-applications/${id}/edit`, // Replace with your actual route for editing
                        method: 'GET',
                        success: function(response) {
                            // Populate the modal with the current leave application data
                            Swal.fire({
                                title: 'Edit Leave Application',
                                width: '600px',
                                html: `
                    <div class="container">
                        <form id="editLeaveForm" class="swal2-form">
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-left">
                                    <label for="edit_from_date" class="form-label mb-0">From Date</label>
                                </div>
                                <div class="col-8">
                                    <input type="date" id="edit_from_date" class="form-control w-100" value="${response.from_date}">
                                </div>
                            </div>
                            
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-left">
                                    <label for="edit_to_date" class="form-label mb-0">To Date</label>
                                </div>
                                <div class="col-8">
                                    <input type="date" id="edit_to_date" class="form-control w-100" value="${response.to_date}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-left">
                                    <label for="edit_status" class="form-label mb-0">Status</label>
                                </div>
                                <div class="col-8">
                                    <select id="edit_status" class="form-control w-100">
                                        <option value="pending" ${response.status === 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="approved" ${response.status === 'approved' ? 'selected' : ''}>Approved</option>
                                        <option value="rejected" ${response.status === 'rejected' ? 'selected' : ''}>Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-left">
                                    <label for="edit_reason" class="form-label mb-0">Reason</label>
                                </div>
                                <div class="col-8">
                                    <textarea id="edit_reason" class="form-control w-100" rows="3">${response.reason || ''}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-left">
                                    <label for="edit_supporting_document" class="form-label mb-0">Supporting Document</label>
                                </div>
                                <div class="col-8">
                                    <input type="file" id="edit_supporting_document" class="form-control w-100">
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
                                    // Get the updated data from the form
                                    let from_date = $('#edit_from_date').val();
                                    let to_date = $('#edit_to_date').val();
                                    let status = $('#edit_status').val();
                                    let reason = $('#edit_reason').val();
                                    let supporting_document = $('#edit_supporting_document')[0]
                                        .files[0];

                                    // Validation
                                    if (!from_date || !to_date) {
                                        Swal.showValidationMessage('Both dates are required');
                                        return false;
                                    }

                                    if (from_date > to_date) {
                                        Swal.showValidationMessage(
                                            'Start date cannot be later than end date');
                                        return false;
                                    }

                                    // Submit the form data via AJAX
                                    let formData = new FormData();
                                    formData.append('_method',
                                        'PUT'); // Laravel will know it's an update
                                    formData.append('from_date', from_date);
                                    formData.append('to_date', to_date);
                                    formData.append('status', status);
                                    formData.append('reason', reason);
                                    if (supporting_document) formData.append(
                                        'supporting_document', supporting_document);

                                    return $.ajax({
                                        url: `/leave-applications/${id}`,
                                        method: 'POST',
                                        data: formData,
                                        processData: false,
                                        contentType: false,
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    }).then(response => {
                                        return response;
                                    }).catch(error => {
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
                                        text: 'Leave application updated successfully.',
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
                                text: 'Failed to load leave application data.',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }

            });
        </script>
    @endpush
@endsection
