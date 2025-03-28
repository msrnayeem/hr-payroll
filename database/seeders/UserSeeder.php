<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;
use App\Models\User;
use Spatie\Permission\Models\Role;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch existing shifts (since they are pre-created)
        $shift1 = Shift::find(1);
        $shift2 = Shift::find(2);

        // Create admin user (no salary card)
        $adminUser = User::create([
            'name' => 'Msr Nayeem',
            'email' => 'msrnayeem@gmail.com',
            'password' => bcrypt('password'),
            'shift_id' => $shift1->id,  // Assign shift
            'salary_card_id' => null,  // No salary card for admin
        ]);

        // Assign admin role
        $adminRole = Role::findByName('admin');
        $adminUser->assignRole($adminRole);

        // Create hr user
        $hrUser = User::create([
            'name' => 'HR User',
            'email' => 'hr@example.com',
            'password' => bcrypt('password'),
            'shift_id' => $shift2->id,  // Assign shift
            'salary_card_id' => null,  // No salary card for HR
        ]);

        // Assign hr role
        $hrRole = Role::findByName('hr');
        $hrUser->assignRole($hrRole);

        // Create employee user
        $employeeUser = User::create([
            'name' => 'Employee User',
            'email' => 'employee@example.com',
            'password' => bcrypt('password'),
            'shift_id' => $shift1->id,  // Assign shift
            'salary_card_id' => null,  // You can assign salary cards here if needed
        ]);

        // Assign employee role
        $employeeRole = Role::findByName('employee');
        $employeeUser->assignRole($employeeRole);
    }
}
