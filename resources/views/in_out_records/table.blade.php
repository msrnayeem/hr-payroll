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
    <h1>In-Out Records</h1>
@endif

<table class="table table-bordered table-hover" id="inOutRecordsTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>ZK Device ID</th>
            <th>SN</th>
            <th>Table</th>
            <th>Stamp</th>
            <th>Timestamp</th>
            <th>Status 1</th>
            <th>Status 2</th>
            <th>Status 3</th>
            <th>Status 4</th>
            <th>Status 5</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inOutRecords as $record)
            <tr>
                <td>{{ $record->id }}</td>
                <td>{{ $record->zk_device_id }}</td>
                <td>{{ $record->sn }}</td>
                <td>{{ $record->table }}</td>
                <td>{{ $record->stamp }}</td>
                <td>{{ $record->timestamp }}</td>
                <td>
                    {!! $record->status1 ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' !!}
                </td>
                <td>
                    {!! $record->status2 ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' !!}
                </td>
                <td>
                    {!! $record->status3 ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' !!}
                </td>
                <td>
                    {!! $record->status4 ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' !!}
                </td>
                <td>
                    {!! $record->status5 ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-danger">No</span>' !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
