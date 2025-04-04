<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $admin = Role::create(['name' => 'admin']);
        $hr = Role::create(['name' => 'hr']);
        $employee = Role::create(['name' => 'employee']);

        // Define Permissions by Category
        // General Permissions
        $permissions = [
            'general' => [
                'view_dashboard' => Permission::create(['name' => 'view_dashboard']),
            ],

            // Employee Management
            'employees' => [
                'view_employees' => Permission::create(['name' => 'view_employees']),
                'add_employee' => Permission::create(['name' => 'add_employee']),
                'edit_employee' => Permission::create(['name' => 'edit_employee']),
            ],

            // Shift Management
            'shifts' => [
                'shift_view' => Permission::create(['name' => 'shift_view']),
                'add_shift' => Permission::create(['name' => 'add_shift']),
                'edit_shift' => Permission::create(['name' => 'edit_shift']),
                'assign_shift' => Permission::create(['name' => 'assign_shift']),
            ],

            // Salary Management
            'salary' => [
                'view_salary_management' => Permission::create(['name' => 'view_salary_management']),
                'view_salary_cards' => Permission::create(['name' => 'view_salary_cards']),
                'add_salary_card' => Permission::create(['name' => 'add_salary_card']),
                'edit_salary_card' => Permission::create(['name' => 'edit_salary_card']),
            ],

            // Salary Components
            'components' => [
                'view_salary_component' => Permission::create(['name' => 'view_salary_component']),
                'create_salary_component' => Permission::create(['name' => 'create_salary_component']),
                'edit_salary_component' => Permission::create(['name' => 'edit_salary_component']),
            ],

            // Payslips
            'payslips' => [
                'view_payslips' => Permission::create(['name' => 'view_payslips']),
                'generate_payslip' => Permission::create(['name' => 'generate_payslip']),
            ],

            // Reports and Settings
            'admin' => [
                'view_reports' => Permission::create(['name' => 'view_reports']),
                'manage_settings' => Permission::create(['name' => 'manage_settings']),
            ],

            // Roles and Permissions Management
            'roles_permissions' => [
                'manage_roles_and_permissions' => Permission::create(['name' => 'manage_roles_and_permissions']),
                'view_roles' => Permission::create(['name' => 'view_roles']),
                'view_permissions' => Permission::create(['name' => 'view_permissions']),
                'create_role' => Permission::create(['name' => 'create_role']),
                'create_permission' => Permission::create(['name' => 'create_permission']),
            ],

            // Holidays
            'holidays' => [
                'view_holidays' => Permission::create(['name' => 'view_holidays']),
                'add_holidays' => Permission::create(['name' => 'add_holidays']),
                'edit_holidays' => Permission::create(['name' => 'edit_holidays']),
            ],

            // Leave Management
            'leaves' => [
                'leave_management' => Permission::create(['name' => 'leave_management']),
                'view_leave_categories' => Permission::create(['name' => 'view_leave_categories']),
                'add_leave_categories' => Permission::create(['name' => 'add_leave_categories']),
                'edit_leave_categories' => Permission::create(['name' => 'edit_leave_categories']),
                'view_leave_applications' => Permission::create(['name' => 'view_leave_applications']),
                'add_leave_applications' => Permission::create(['name' => 'add_leave_applications']),
                'edit_leave_applications' => Permission::create(['name' => 'edit_leave_applications']),
                'take_leave_decision' => Permission::create(['name' => 'take_leave_decision']),
            ],

            'attendance' => [
                'attendance_view' => Permission::create(['name' => 'attendance_view']),
                'attendance_edit' => Permission::create(['name' => 'attendance_edit']),
                'attendance_request' => Permission::create(['name' => 'attendance_request']),
                'edit_attendance_request' => Permission::create(['name' => 'edit_attendance_request']),
                'attendance_request_decision' => Permission::create(['name' => 'attendance_request_decision']),
                'attendance_report' => Permission::create(['name' => 'attendance_report']),
                'in_out_record' => Permission::create(['name' => 'in_out_record']),
                'in_out_record_report' => Permission::create(['name' => 'in_out_record_report']),
            ],
        ];

        // Assign Permissions to Roles
        // Admin - Full access
        $admin->givePermissionTo(array_merge(
            $permissions['general'],
            $permissions['employees'],
            $permissions['shifts'],
            $permissions['salary'],
            $permissions['components'],
            $permissions['payslips'],
            $permissions['admin'],
            $permissions['roles_permissions'],
            $permissions['holidays'],
            $permissions['leaves'],
            $permissions['attendance']
        ));

        // HR - Most access except roles/permissions management and settings
        $hr->givePermissionTo(array_merge(
            $permissions['general'],
            $permissions['employees'],
            $permissions['salary'],
            $permissions['components'],
            $permissions['payslips'],
            [$permissions['admin']['view_reports']],
            $permissions['holidays'],
            $permissions['leaves'],
            $permissions['attendance'],
            $permissions['shifts'],
        ));

        // Employee - Limited access
        $employee->givePermissionTo([
            $permissions['general']['view_dashboard'],
            $permissions['salary']['view_salary_management'],
            $permissions['salary']['view_salary_cards'],
            $permissions['payslips']['view_payslips'],
            $permissions['components']['view_salary_component'],
            $permissions['holidays']['view_holidays'],
            $permissions['leaves']['view_leave_categories'],
            $permissions['leaves']['view_leave_applications'],
            $permissions['leaves']['add_leave_applications'],
            $permissions['leaves']['edit_leave_applications'],
            $permissions['attendance']['attendance_view'],
            $permissions['attendance']['in_out_record'],
            $permissions['attendance']['attendance_request'],
            $permissions['attendance']['edit_attendance_request'],
            $permissions['shifts']['shift_view'],
        ]);
    }
}
