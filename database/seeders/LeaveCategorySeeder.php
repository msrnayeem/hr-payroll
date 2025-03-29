<?php

namespace Database\Seeders;

use App\Models\LeaveCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class LeaveCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            ['name' => 'Sick Leave', 'max_days' => 14, 'requires_approval' => true],
            ['name' => 'Casual Leave', 'max_days' => 10, 'requires_approval' => true],
            ['name' => 'Maternity Leave', 'max_days' => 16 * 7, 'requires_approval' => true],
            ['name' => 'Paternity Leave', 'max_days' => 7, 'requires_approval' => true],
            ['name' => 'Unpaid Leave', 'max_days' => null, 'requires_approval' => true],
        ];

        foreach ($leaveTypes as $type) {
            LeaveCategory::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
