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
    <h1>Attendance List</h1>
@endif

<table class="table table-bordered table-hover" id="attendanceTable">
    <thead>
        <tr>
            <th>ID</th>
            @if (!isset($export))
                <th>Image</th>
            @endif
            <th>Name</th>
            <th>Date</th>
            <th>Shift</th>
            <th>Entry</th>
            <th>Exit</th>
            <th>Late</th>
            <th>Early</th>
            <th>Manual</th>
            <th>Edited By</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($attendances as $attendance)
            <tr>
                <td>{{ $attendance->id }}</td>

                @if (!isset($export))
                    <td>
                        @if ($attendance->employee->profile_image)
                            <img src="{{ Storage::url($attendance->employee->profile_image) }}" alt="Employee Image"
                                width="50" height="50" class="img-thumbnail">
                        @else
                            N/A
                        @endif
                    </td>
                @endif

                <td>{{ $attendance->employee->name ?? 'N/A' }}</td>
                <td>{{ $attendance->attendance_date }}</td>
                <td>
                    {{ $attendance->shift_start . ' - ' . $attendance->shift_end }}
                </td>
                <td>{{ $attendance->entry_time }}</td>
                <td>{{ $attendance->exit_time }}</td>
                <td>
                    {!! $attendance->is_late
                        ? '<span class="badge bg-danger">Yes</span>'
                        : '<span class="badge bg-success">No</span>' !!}
                </td>
                <td>
                    {!! $attendance->is_early
                        ? '<span class="badge bg-warning">Yes</span>'
                        : '<span class="badge bg-success">No</span>' !!}
                </td>
                <td>{{ $attendance->is_manual ? 'Yes' : 'No' }}</td>
                <td>{{ $attendance->manualBy->name ?? 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
