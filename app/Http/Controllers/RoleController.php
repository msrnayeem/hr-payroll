<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controller;


class RoleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:manage_roles', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);
        // $this->middleware('permission:manage_permissions', ['only' => ['permissionIndex', 'createPermission', 'storePermission', 'editPermission', 'updatePermission', 'destroyPermission']]);
    }

    // Role Management
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('role.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('role.create', compact('permissions'));
    }

    public function store(Request $request)
    {

        // Validate the role name and permissions to ensure proper data
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',  // Ensure permission IDs exist
        ]);

        // Create the role
        $role = Role::create(['name' => $request->name]);

        // Assign permissions to the role
        $role->givePermissionTo($request->permissions);

        return redirect()->route('roles.index');
    }

    public function update(Request $request, Role $role)
    {

        // Validate the role name and permissions
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name,' . $role->id  // Ensure unique, except for current role
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        // Update the role name
        $role->update(['name' => $validatedData['name']]);

        $role->permissions()->detach();

        // Assign all specified permissions to the role
        $role->permissions()->attach($validatedData['permissions']);


        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('role.edit', compact('role', 'permissions'));
    }
}
