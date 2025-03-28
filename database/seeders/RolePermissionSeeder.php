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

        // Salary Component permissions
        $create_salary_component = Permission::create(['name' => 'create_salary_component']);
        $edit_salary_component = Permission::create(['name' => 'edit_salary_component']);
        $view_salary_component = Permission::create(['name' => 'view_salary_component']);


        //create permissions for the roles
        $manageRolesPermissions = Permission::create(['name' => 'manage_roles_and_permissions']);
        $viewRoles = Permission::create(['name' => 'view_roles']);
        $viewPermissions = Permission::create(['name' => 'view_permissions']);
        $createRole = Permission::create(['name' => 'create_role']);
        $createPermission = Permission::create(['name' => 'create_permission']);

        $viewHolidays = Permission::create(['name' => 'view_holidays']);
        $addHolidays = Permission::create(['name' => 'add_holidays']);
        $editHolidays = Permission::create(['name' => 'edit_holidays']);


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
            $create_salary_component,
            $view_salary_component,
            $edit_salary_component,
            $manageRolesPermissions,
            $viewRoles,
            $viewPermissions,
            $createRole,
            $createPermission,
            $viewHolidays,
            $addHolidays,
            $editHolidays,
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
            $create_salary_component,
            $view_salary_component,
            $edit_salary_component,
            $viewHolidays,
            $addHolidays,
            $editHolidays,
        ]);

        $employee->givePermissionTo([
            $viewDashboard,
            $viewPayslips,
            $view_salary_component,
            $viewHolidays,
        ]);
    }
}
