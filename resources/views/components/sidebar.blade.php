<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">HR SOFT</span>
        </a>
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                @can('view_dashboard')
                    <li class="nav-item menu-open">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="nav-icon bi bi-speedometer"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endcan

                <!-- Employee Management -->
                @can('view_employees')
                    <li class="nav-item mt-3">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-people-fill"></i>
                            <p>
                                Employee Management
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('view_employees')
                                <li class="nav-item">
                                    <a href="{{ route('employees.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-list"></i>
                                        <p>View Employees</p>
                                    </a>
                                </li>
                            @endcan

                            @can('add_employee')
                                <li class="nav-item">
                                    <a href="{{ route('employees.create') }}" class="nav-link">
                                        <i class="nav-icon bi bi-plus-circle"></i>
                                        <p>Add Employee</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Salary Management -->
                @can('view_salary_management')
                    <li class="nav-item mt-3">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-currency-dollar"></i>
                            <p>
                                Salary Management
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            @can('view_salary_cards')
                                <li class="nav-item">
                                    <a href="{{ route('salary-cards.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-credit-card"></i>
                                        <p>Salary Cards</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view_salary_component')
                                <li class="nav-item">
                                    <a href="{{ route('salarycomponent.index', ['type' => 'earning']) }}" class="nav-link">
                                        <i class="nav-icon bi bi-plus-square"></i>
                                        <p>Earn Heads</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view_salary_component')
                                <li class="nav-item">
                                    <a href="{{ route('salarycomponent.index', ['type' => 'deduction']) }}" class="nav-link">
                                        <i class="nav-icon bi bi-dash-square"></i>
                                        <p>Deductions</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan

                <!-- Payslips -->
                @can('view_payslips')
                    <li class="nav-item mt-3">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-file-earmark-text"></i>
                            <p>
                                Payslips
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('generate_payslip')
                                <li class="nav-item">
                                    <a href="{{ route('payslips.create') }}" class="nav-link">
                                        <i class="nav-icon bi bi-file-earmark-plus"></i>
                                        <p>Generate Payslip</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view_payslips')
                                <li class="nav-item">
                                    <a href="{{ route('payslips.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-clock-history"></i>
                                        <p>Payslip History</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Reports -->
                @can('view_reports')
                    <li class="nav-item mt-3">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-bar-chart-line"></i>
                            <p>
                                Reports
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('view_reports')
                                <li class="nav-item">
                                    <a href="{{ route('reports.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-file-bar-graph"></i>
                                        <p>Salary Reports</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Roles and Permissions -->
                @can('manage_roles_and_permissions')
                    <li class="nav-item mt-3">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-shield-lock"></i>
                            <p>
                                Roles & Permissions
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('view_roles')
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-diagram-3"></i>
                                        <p>Roles</p>
                                    </a>
                                </li>
                            @endcan

                            @can('view_permissions')
                                <li class="nav-item">
                                    <a href="{{ route('permissions.index') }}" class="nav-link">
                                        <i class="nav-icon bi bi-check2-square"></i>
                                        <p>Permissions</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                <!-- Settings -->
                @can('manage_settings')
                    <li class="nav-item mt-3">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-gear-fill"></i>
                            <p>
                                Settings
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('manage_settings')
                                <li class="nav-item">
                                    <a href="{{ route('settings.system') }}" class="nav-link">
                                        <i class="nav-icon bi bi-sliders"></i>
                                        <p>System Settings</p>
                                    </a>
                                </li>
                            @endcan

                            @can('manage_settings')
                                <li class="nav-item">
                                    <a href="{{ route('settings.users') }}" class="nav-link">
                                        <i class="nav-icon bi bi-people"></i>
                                        <p>User Management</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
