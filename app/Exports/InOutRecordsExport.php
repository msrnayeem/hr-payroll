<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InOutRecordsExport implements FromCollection, WithHeadings
{
    protected $inOutRecords;

    public function __construct(Collection $inOutRecords)
    {
        $this->inOutRecords = $inOutRecords;
    }

    public function collection()
    {
        return $this->inOutRecords->map(function ($record) {
            return [
                'ID' => $record->id,
                'SN' => $record->sn,
                'Table' => $record->table,
                'Stamp' => $record->stamp,
                'ZK Device ID' => $record->zk_device_id,
                'Timestamp' => $record->timestamp,
                'Status 1' => $record->status1 ? 'Yes' : 'No',
                'Status 2' => $record->status2 ? 'Yes' : 'No',
                'Status 3' => $record->status3 ? 'Yes' : 'No',
                'Status 4' => $record->status4 ? 'Yes' : 'No',
                'Status 5' => $record->status5 ? 'Yes' : 'No',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'SN',
            'Table',
            'Stamp',
            'ZK Device ID',
            'Timestamp',
            'Status 1',
            'Status 2',
            'Status 3',
            'Status 4',
            'Status 5',
        ];
    }
}
