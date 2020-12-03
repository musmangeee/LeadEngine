<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeedRedirectManagementPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::where(['name' => 'Admin', 'guard_name' => 'api'])->first();

        Permission::create(['name' => 'Redirect.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Redirect.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Redirect.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Redirect.delete', 'guard_name' => 'api'])->assignRole($role);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
