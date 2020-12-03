<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InitializeRolesPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::create(['name' => 'Admin', 'guard_name' => 'api']);

        //create logo permissions
        Permission::create(['name' => 'Logo.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Logo.update', 'guard_name' => 'api'])->assignRole($role);

        //create user permissions
        Permission::create(['name' => 'User.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'User.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'User.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'User.delete', 'guard_name' => 'api'])->assignRole($role);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
