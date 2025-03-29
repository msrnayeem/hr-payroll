@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Holidays';
        $breadcrumbs = [['title' => 'Holidays', 'url' => route('holidays.index')]];
        $breadcrumbs[] = ['title' => 'All Holidays'];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Holidays List</h3>
                @can('add_holidays')
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" onclick="openCreateModal()">
                            <i class="fas fa-plus"></i> New Holiday
                        </button>
                    </div>
                @endcan
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover" id="holidaysTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Status</th>
                            @can('edit_holidays')
                                <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $holiday)
                            <tr id="holidayRow-{{ $holiday->id }}">
                                <td>{{ $holiday->name }}</td>
                                <td>{{ $holiday->from_date }}</td>
                                <td>{{ $holiday->to_date }}</td>
                                <td>
                                    {{ ucfirst($holiday->status) }}
                                </td>
                                @can('edit_holidays')
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $holiday->id }})">
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
                    title: 'Add New Holiday',
                    width: '600px',
                    html: `
            <div class="container">
                <form id="holidayForm" class="swal2-form">
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 text-left">
                            <label for="name" class="form-label mb-0">Holiday Name</label>
                        </div>
                        <div class="col-8">
                            <input id="name" class="form-control w-100" placeholder="Enter Holiday Name">
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
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
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
                        let from_date = $('#from_date').val();
                        let to_date = $('#to_date').val();
                        let status = $('#status').val();

                        // Basic validation
                        if (!name) {
                            Swal.showValidationMessage('Holiday name is required');
                            return false;
                        }
                        if (!from_date || !to_date) {
                            Swal.showValidationMessage('Both dates are required');
                            return false;
                        }

                        // Validate start date is not from previous month
                        const currentDate = new Date();
                        const currentMonth = currentDate.getMonth();
                        const currentYear = currentDate.getFullYear();
                        const startDate = new Date(from_date);
                        const startMonth = startDate.getMonth();
                        const startYear = startDate.getFullYear();

                        if ((startYear < currentYear) || (startYear === currentYear && startMonth < currentMonth)) {
                            Swal.showValidationMessage('Start date cannot be from a previous month');
                            return false;
                        }

                        // Validate end date is after start date
                        if (from_date > to_date) {
                            Swal.showValidationMessage('Start date cannot be later than end date');
                            return false;
                        }

                        // Submit form
                        return $.ajax({
                                url: '{{ route('holidays.store') }}',
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    name: name,
                                    from_date: from_date,
                                    to_date: to_date,
                                    status: status
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
                            text: 'Holiday added successfully.',
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
                    url: `/holidays/${id}/edit`,
                    method: 'GET',
                    success: function(response) {
                        Swal.fire({
                            title: 'Edit Holiday',
                            width: '600px',
                            html: `
                    <div class="container">
                        <form id="editHolidayForm" class="swal2-form">
                            <div class="row mb-3 align-items-center">
                                <div class="col-4 text-left">
                                    <label for="edit_name" class="form-label mb-0">Holiday Name</label>
                                </div>
                                <div class="col-8">
                                    <input id="edit_name" class="form-control w-100" value="${response.name}">
                                </div>
                            </div>
                            
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
                                        <option value="active" ${response.status === 'active' ? 'selected' : ''}>Active</option>
                                        <option value="inactive" ${response.status === 'inactive' ? 'selected' : ''}>Inactive</option>
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
                                let from_date = $('#edit_from_date').val();
                                let to_date = $('#edit_to_date').val();
                                let status = $('#edit_status').val();

                                // Basic validation
                                if (!name) {
                                    Swal.showValidationMessage('Holiday name is required');
                                    return false;
                                }
                                if (!from_date || !to_date) {
                                    Swal.showValidationMessage('Both dates are required');
                                    return false;
                                }

                                // Validate start date is not from previous month
                                const currentDate = new Date();
                                const currentMonth = currentDate.getMonth();
                                const currentYear = currentDate.getFullYear();
                                const startDate = new Date(from_date);
                                const startMonth = startDate.getMonth();
                                const startYear = startDate.getFullYear();

                                if ((startYear < currentYear) || (startYear === currentYear &&
                                        startMonth < currentMonth)) {
                                    Swal.showValidationMessage(
                                        'Start date cannot be from a previous month');
                                    return false;
                                }

                                // Validate end date is after start date
                                if (from_date > to_date) {
                                    Swal.showValidationMessage(
                                        'Start date cannot be later than end date');
                                    return false;
                                }

                                // Submit form
                                return $.ajax({
                                        url: `/holidays/${id}`,
                                        method: 'PUT',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        data: {
                                            name: name,
                                            from_date: from_date,
                                            to_date: to_date,
                                            status: status
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
                                    text: 'Holiday updated successfully.',
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
                            text: 'Failed to load holiday data.',
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
