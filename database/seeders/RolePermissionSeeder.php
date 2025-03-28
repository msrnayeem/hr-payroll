<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $hr = Role::create(['name' => 'hr']);
        $employee = Role::create(['name' => 'employee']);

        // Create permissions
        $viewDashboard = Permission::create(['name' => 'view_dashboard']);
        $viewEmployees = Permission::create(['name' => 'view_employees']);
        $addEmployee = Permission::create(['name' => 'add_employee']);
        $viewPayslips = Permission::create(['name' => 'view_payslips']);
        $generatePayslip = Permission::create(['name' => 'generate_payslip']);
        $viewReports = Permission::create(['name' => 'view_reports']);
        $manageSettings = Permission::create(['name' => 'manage_settings']);

        // Salary Management permissions
        $viewSalaryManagement = Permission::create(['name' => 'view_salary_management']);
        $viewSalaryCards = Permission::create(['name' => 'view_salary_cards']);
        $addSalaryCard = Permission::create(['name' => 'add_salary_card']);
        $viewEarnHeads = Permission::create(['name' => 'view_earn_heads']);
        $viewDeductionCategories = Permission::create(['name' => 'view_deduction_categories']);

        // Assign permissions to roles
        $admin->givePermissionTo([
            $viewDashboard,
            $viewEmployees,
            $addEmployee,
            $viewSalaryManagement,
            $viewSalaryCards,
            $addSalaryCard,
            $viewPayslips,
            $generatePayslip,
            $viewReports,
            $manageSettings,
            $viewEarnHeads,
            $viewDeductionCategories
        ]);

        $hr->givePermissionTo([
            $viewDashboard,
            $viewEmployees,
            $addEmployee,
            $viewSalaryManagement,
            $viewSalaryCards,
            $addSalaryCard,
            $viewPayslips,
            $generatePayslip,
            $viewReports,
            $viewEarnHeads,
            $viewDeductionCategories
        ]);

        $employee->givePermissionTo([
            $viewDashboard,
            $viewPayslips
        ]);
    }
}
