<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert default shifts using Eloquent
        Shift::create([
            'name' => 'Main',
            'start_time' => '10:00:00',
            'end_time' => '20:00:00',
            'saturday' => true,
            'sunday' => true,
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => false, // Friday off
            'late_time' => '10:00:00',
            'early_time' => '20:00:00',
        ]);

        Shift::create([
            'name' => 'Special',
            'start_time' => '10:00:00',
            'end_time' => '18:00:00',
            'saturday' => false, // Saturday off
            'sunday' => true,
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => false, // Friday off
            'late_time' => '10:15:00',
            'early_time' => '17:45:00',
        ]);
    }
}
