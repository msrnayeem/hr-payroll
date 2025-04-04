@if (isset($export))
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #007bff;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
    <h1>Employee List</h1>
@endif

<table class="table table-bordered table-hover" id="employeesTable">
    <thead>
        <tr>
            <th>ID</th>
            @if (!isset($export))
                <th>Image</th>
            @endif
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Shift Name</th>
            <th>Status</th>
            <th>Salary Card</th>
            @if (!isset($export))
                <th>Actions</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->id }}</td>
                @if (!isset($export))
                    <td>
                        @if ($employee->profile_image)
                            <img src="{{ Storage::url($employee->profile_image) }}" alt="Employee Image" width="50"
                                height="50" class="img-thumbnail">
                        @else
                            N/A
                        @endif
                    </td>
                @endif
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->phone }}</td>
                <td>{{ $employee->shift->name ?? 'N\A' }}</td>
                <td>{{ $employee->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    @if ($employee->salary_card_id)
                        <a href="{{ route('salary-cards.show', $employee->salary_card_id) }}">
                            View
                        </a>
                    @else
                        N/A
                    @endif
                </td>
                @if (!isset($export))
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>

                            @can('edit_employee')
                                @if ($employee->is_active)
                                    <!-- Edit Button -->
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Deactivate Button -->
                                    <form action="{{ route('employees.update-status', $employee->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="is_active" value="0">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-ban"></i> Deactivate
                                        </button>
                                    </form>
                                @else
                                    <!-- Activate Button -->
                                    <form action="{{ route('employees.update-status', $employee->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="is_active" value="1">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Activate
                                        </button>
                                    </form>
                                @endif
                            @endcan

                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
