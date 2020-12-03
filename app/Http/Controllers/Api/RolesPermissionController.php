<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionController extends Controller
{
    public function getAllPermissionInGroup()
    {
        $permissions = Permission::all();

        $groupedPermissions = [];

        foreach ($permissions as $permission) {
            $explodedPermission = explode('.', $permission->name);
            if (isset($groupedPermissions[$explodedPermission[0]])) {
                $groupedPermissions[$explodedPermission[0]] = array_merge($groupedPermissions[$explodedPermission[0]], [$explodedPermission[1]]);
            } else {
                $groupedPermissions = array_add($groupedPermissions, $explodedPermission[0], [$explodedPermission[1]]);
            }
        }

        return response()->json($groupedPermissions, 200);
    }

    public function getAllRolesWithPermissionInGroup()
    {
        $roles = Role::with('permissions')->get();
        $groupedRolesAndPermissions = [];

        foreach ($roles as $role) {
            $groupedRolesAndPermissions[$role->name] = [];

            foreach ($role->permissions as $permission) {
                $explodedPermission = explode('.', $permission->name);
                if (isset($groupedRolesAndPermissions[$role->name][$explodedPermission[0]])) {
                    $groupedRolesAndPermissions[$role->name][$explodedPermission[0]] = array_merge($groupedRolesAndPermissions[$role->name][$explodedPermission[0]], [$explodedPermission[1]]);
                } else {
                    $groupedRolesAndPermissions[$role->name] = array_add($groupedRolesAndPermissions[$role->name], $explodedPermission[0], [$explodedPermission[1]]);
                }
            }
        }

        return response()->json($groupedRolesAndPermissions, 200);
    }
}
