@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Attendance Requests';
        $breadcrumbs = [['title' => 'Attendance Requests', 'url' => route('attendances-requests.index')]];
        $breadcrumbs[] = ['title' => 'All Attendance Requests'];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Attendance Requests</h3>
                @can('attendance_request')
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" id="createAttendanceRequestBtn">
                            <i class="fas fa-plus"></i>Attendance Request
                        </button>
                    </div>
                @endcan
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover" id="attendanceRequestsTable">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Date</th>
                            <th>Entry Time</th>
                            <th>Exit Time</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th>Decided By</th>
                            @can('edit_attendance_request')
                                <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendanceRequests as $attendanceRequest)
                            <tr id="attendanceRequestRow-{{ $attendanceRequest->id }}">
                                <td>{{ $attendanceRequest->employee->name }}</td>
                                <td>{{ $attendanceRequest->attendance_date }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendanceRequest->entry_time)->format('h:i A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendanceRequest->exit_time)->format('h:i A') }}</td>
                                <td>{{ ucfirst($attendanceRequest->status) }}</td>
                                <td>{{ $attendanceRequest->reason ?? 'N/A' }}</td>
                                <td>{{ $attendanceRequest->decidedBy->name ?? 'N/A' }}</td>
                                @can('edit_attendance_request')
                                    <td>
                                        @if ($attendanceRequest->status == 'pending')
                                            <button class="btn btn-warning btn-sm editAttendanceRequestBtn"
                                                data-id="{{ $attendanceRequest->id }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        @endif

                                        @can('attendance_request_decision')
                                            @if ($attendanceRequest->status == 'pending')
                                                <button class="btn btn-warning btn-sm decideAttendanceRequestBtn"
                                                    data-id="{{ $attendanceRequest->id }}">
                                                    <i class="fas fa-edit"></i> Decide
                                                </button>
                                            @endif
                                        @endcan
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
            $(document).ready(function() {
                // Create new attendance request
                $('#createAttendanceRequestBtn').click(function() {
                    openCreateModal();
                });

                // Edit attendance request
                $('.editAttendanceRequestBtn').click(function() {
                    const attendanceRequestId = $(this).data('id');
                    openEditModal(attendanceRequestId);
                });

                // Decide attendance request
                $('.decideAttendanceRequestBtn').click(function() {
                    const attendanceRequestId = $(this).data('id');
                    openDecideModal(attendanceRequestId);
                });

                function openCreateModal() {
                    Swal.fire({
                        title: 'Create Attendance Request',
                        width: '600px',
                        html: `
                            <div class="container">
                                <form id="attendanceRequestForm" class="swal2-form">
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="employee_id" class="form-label mb-0">Employee</label>
                                        </div>
                                        <div class="col-8">
                                            <select id="employee_id" class="form-control w-100">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="attendance_date" class="form-label mb-0">Date</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="date" id="attendance_date" class="form-control w-100">
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="entry_time" class="form-label mb-0">Entry Time</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="time" id="entry_time" class="form-control w-100">
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-4 text-left">
                                            <label for="exit_time" class="form-label mb-0">Exit Time</label>
                                        </div>
                                        <div class="col-8">
                                            <input type="time" id="exit_time" class="form-control w-100">
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
                            let employee_id = $('#employee_id').val();
                            let attendance_date = $('#attendance_date').val();
                            let entry_time = $('#entry_time').val();
                            let exit_time = $('#exit_time').val();
                            let reason = $('#reason').val();

                            if (!employee_id || !attendance_date || !entry_time || !exit_time) {
                                Swal.showValidationMessage('Required fields are missing');
                                return false;
                            }

                            const formData = new FormData();
                            formData.append('employee_id', employee_id);
                            formData.append('attendance_date', attendance_date);
                            formData.append('entry_time', entry_time);
                            formData.append('exit_time', exit_time);
                            formData.append('reason', reason);

                            return $.ajax({
                                url: '{{ route('attendances-requests.store') }}',
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
                                text: 'Attendance request submitted successfully.',
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
                        url: `/attendances-requests/${id}/edit`,
                        method: 'GET',
                        success: function(response) {

                            Swal.fire({
                                title: 'Edit Attendance Request',
                                width: '600px',
                                html: `
                                    <div class="container">
                                        <form id="editAttendanceForm" class="swal2-form">
                                            <div class="row mb-3 align-items-center">
                                                <div class="col-4 text-left">
                                                    <label for="edit_attendance_date" class="form-label mb-0">Date</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="date" id="edit_attendance_date" class="form-control w-100" value="${response.data.attendance_date}">
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <div class="col-4 text-left">
                                                    <label for="edit_entry_time" class="form-label mb-0">Entry Time</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="time" id="edit_entry_time" class="form-control w-100" value="${response.data.entry_time}">
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <div class="col-4 text-left">
                                                    <label for="edit_exit_time" class="form-label mb-0">Exit Time</label>
                                                </div>
                                                <div class="col-8">
                                                    <input type="time" id="edit_exit_time" class="form-control w-100" value="${response.data.exit_time}">
                                                </div>
                                            </div>

                                            <div class="row mb-3 align-items-center">
                                                <div class="col-4 text-left">
                                                    <label for="edit_reason" class="form-label mb-0">Reason</label>
                                                </div>
                                                <div class="col-8">
                                                    <textarea id="edit_reason" class="form-control w-100" rows="3">${response.data.reason || ''}</textarea>
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
                                    let attendance_date = $('#edit_attendance_date').val();
                                    let entry_time = $('#edit_entry_time').val();
                                    let exit_time = $('#edit_exit_time').val();
                                    let reason = $('#edit_reason').val();

                                    if (!attendance_date || !entry_time || !exit_time) {
                                        Swal.showValidationMessage(
                                            'Required fields are missing');
                                        return false;
                                    }

                                    let formData = new FormData();
                                    formData.append('_method', 'PUT');
                                    formData.append('attendance_date', attendance_date);
                                    formData.append('entry_time', entry_time);
                                    formData.append('exit_time', exit_time);
                                    formData.append('reason', reason);

                                    return $.ajax({
                                        url: `/attendances-requests/${id}/update`,
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
                                        text: 'Attendance request updated successfully.',
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
                                text: 'Failed to load attendance request data.',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }

                function openDecideModal(id) {
                    Swal.fire({
                        title: 'Attendance Request Decision',
                        text: 'Are you sure you want to approve or reject this attendance request?',
                        width: '400px',
                        showCancelButton: true,
                        showCloseButton: true,
                        confirmButtonText: 'Approve',
                        cancelButtonText: 'Reject',
                        customClass: {
                            container: 'container-fluid',
                            confirmButton: 'btn btn-primary me-2',
                            cancelButton: 'btn btn-danger me-2'
                        },
                        buttonsStyling: false,
                        focusConfirm: false,
                        preConfirm: () => {
                            return submitAttendanceDecision(id, 'approved');
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            return submitAttendanceDecision(id, 'rejected');
                        }
                    });
                }

                function submitAttendanceDecision(id, status) {
                    let formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('status', status);

                    return $.ajax({
                        url: `/attendances-requests/${id}/update`,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(response => {
                        Swal.fire({
                            title: 'Success!',
                            text: `Attendance request has been ${status}.`,
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        }).then(() => {
                            location.reload();
                        });
                    }).catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: error.responseJSON?.message || 'An error occurred.',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    });
                }
            });
        </script>
    @endpush
@endsection
