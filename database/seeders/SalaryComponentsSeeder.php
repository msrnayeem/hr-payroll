<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryComponentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salaryComponents = [
            ['name' => 'Medical Allowance', 'type' => 'earning'],
            ['name' => 'Transport Allowance', 'type' => 'earning'],
            ['name' => 'Mobile Bill', 'type' => 'earning'],
            ['name' => 'Provident Fund', 'type' => 'deduction'],
            ['name' => 'Health Insurance', 'type' => 'deduction'],
        ];

        foreach ($salaryComponents as $component) {
            DB::table('salary_components')->updateOrInsert(
                ['name' => $component['name']],
                ['type' => $component['type'], 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
