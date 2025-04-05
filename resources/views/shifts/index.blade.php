@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Shifts';
        $breadcrumbs = [['title' => 'Shifts', 'url' => route('shifts.index')]];
        $breadcrumbs[] = ['title' => 'All Shifts'];
    @endphp

    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Shifts List</h3>
                @can('add_shift')
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" onclick="openModal('create')">
                            <i class="fas fa-plus"></i> New Shift
                        </button>
                    </div>
                @endcan
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover" id="shiftsTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Late Time</th>
                            <th>Early Time</th>
                            <th>Saturday</th>
                            <th>Sunday</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            @can('edit_shift')
                                <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shifts as $shift)
                            <tr>
                                <td>{{ $shift->name }}</td>
                                <td>{{ format12hr($shift->start_time) }}</td>
                                <td>{{ format12hr($shift->end_time) }}</td>
                                <td>{{ format12hr($shift->late_time) }}</td>
                                <td>{{ format12hr($shift->early_time) }}</td>
                                <td>{{ $shift->saturday ? '✓' : '✗' }}</td>
                                <td>{{ $shift->sunday ? '✓' : '✗' }}</td>
                                <td>{{ $shift->monday ? '✓' : '✗' }}</td>
                                <td>{{ $shift->tuesday ? '✓' : '✗' }}</td>
                                <td>{{ $shift->wednesday ? '✓' : '✗' }}</td>
                                <td>{{ $shift->thursday ? '✓' : '✗' }}</td>
                                <td>{{ $shift->friday ? '✓' : '✗' }}</td>
                                @can('edit_shift')
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="openModal('edit', {{ $shift->id }})">
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            // Days array for reuse
            const days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

            // Common form fields for both create and edit forms
            function getFormFields(type, data = {}) {
                const prefix = type === 'edit' ? 'edit_' : '';
                const formFields = [{
                        label: 'Shift Name',
                        id: 'name',
                        type: 'text',
                        placeholder: 'Enter Shift Name',
                        value: data.name || ''
                    },
                    {
                        label: 'Start Time',
                        id: 'start_time',
                        type: 'time',
                        value: data.start_time || '10:00'
                    },
                    {
                        label: 'End Time',
                        id: 'end_time',
                        type: 'time',
                        value: data.end_time || '18:00'
                    },
                    {
                        label: 'Late Allowance',
                        id: 'late_time',
                        type: 'time',
                        value: data.late_time || '10:15'
                    },
                    {
                        label: 'Early Leave Allowance',
                        id: 'early_time',
                        type: 'time',
                        value: data.early_time || '17:45'
                    }
                ];

                let html = `<div class="container"><form id="${type}ShiftForm" class="swal2-form">`;

                // Generate input fields
                formFields.forEach(field => {
                    html += `
                    <div class="row mb-3 align-items-center">
                        <div class="col-4 text-left">
                            <label for="${prefix}${field.id}" class="form-label mb-0">${field.label}</label>
                        </div>
                        <div class="col-8">
                            <input type="${field.type}" id="${prefix}${field.id}" class="form-control w-100" 
                                ${field.placeholder ? `placeholder="${field.placeholder}"` : ''}
                                value="${field.value}">
                        </div>
                    </div>`;
                });

                // Generate days checkboxes
                html += `
                <div class="row mb-2">
                    <div class="col-12">
                        <label class="form-label">Active Days</label>
                        <div class="d-flex flex-wrap gap-2">`;

                days.forEach(day => {
                    const isChecked = type === 'edit' ? (data[day] == 1) : true;
                    html += `
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="${prefix}${day}" ${isChecked ? 'checked' : ''}>
                        <label class="form-check-label" for="${prefix}${day}">${day.charAt(0).toUpperCase() + day.slice(1)}</label>
                    </div>`;
                });

                html += `</div></div></div></form></div>`;
                return html;
            }

            // Unified function to open modal for both create and edit
            function openModal(type, id = null) {
                if (type === 'edit' && id === null) {
                    console.error('ID is required for edit mode');
                    return;
                }

                const isEdit = type === 'edit';

                // For edit, first get the data
                if (isEdit) {
                    $.ajax({
                        url: `/shifts/${id}/edit`,
                        method: 'GET',
                        success: function(response) {
                            showModal(type, response, id);
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to load shift data.',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                } else {
                    // For create, show modal directly
                    showModal(type);
                }
            }

            // Function to show the modal with appropriate content
            function showModal(type, data = {}, id = null) {
                const isEdit = type === 'edit';
                const prefix = isEdit ? 'edit_' : '';

                Swal.fire({
                    title: isEdit ? 'Edit Shift' : 'Add New Shift',
                    width: '700px',
                    html: getFormFields(type, data),
                    showCancelButton: true,
                    confirmButtonText: isEdit ? 'Update' : 'Save',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        container: 'container-fluid',
                        confirmButton: 'btn btn-primary me-2',
                        cancelButton: 'btn btn-secondary me-2'
                    },
                    buttonsStyling: false,
                    focusConfirm: false,
                    preConfirm: () => {
                        // Get form values
                        let formData = {
                            name: $(`#${prefix}name`).val().trim(),
                            start_time: $(`#${prefix}start_time`).val(),
                            end_time: $(`#${prefix}end_time`).val(),
                            late_time: $(`#${prefix}late_time`).val(),
                            early_time: $(`#${prefix}early_time`).val()
                        };

                        // Validation
                        if (!formData.name || !formData.start_time || !formData.end_time) {
                            Swal.showValidationMessage('Name, start time, and end time are required');
                            return false;
                        }

                        // Get days values
                        days.forEach(day => {
                            formData[day] = $(`#${prefix}${day}`).is(':checked') ? 1 : 0;
                        });

                        // Make AJAX request
                        return $.ajax({
                                url: isEdit ? `/shifts/${id}` : '{{ route('shifts.store') }}',
                                method: isEdit ? 'PUT' : 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: formData
                            })
                            .then(response => response)
                            .catch(error => {
                                let message = 'Unknown error';
                                if (error.responseJSON?.errors) {
                                    const firstKey = Object.keys(error.responseJSON.errors)[0];
                                    message = error.responseJSON.errors[firstKey][0];
                                } else if (error.responseJSON?.message) {
                                    message = error.responseJSON.message;
                                }
                                Swal.showValidationMessage(`Request failed: ${message}`);
                                return false;
                            });
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Success!',
                            text: isEdit ? 'Shift updated successfully.' : 'Shift added successfully.',
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        }).then(() => location.reload());
                    }
                });
            }
        </script>
    @endpush
@endsection
