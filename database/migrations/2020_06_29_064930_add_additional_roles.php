<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddAdditionalRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $role = Role::where(['name' => 'Admin', 'guard_name' => 'api'])->first();

        Permission::create(['name' => 'Buyer.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer.delete', 'guard_name' => 'api'])->assignRole($role);

        Permission::create(['name' => 'Buyer-panda-channel.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer-panda-channel.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer-panda-channel.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer-panda-channel.delete', 'guard_name' => 'api'])->assignRole($role);

        Permission::create(['name' => 'Buyer-plat-channel.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer-plat-channel.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer-plat-channel.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer-plat-channel.delete', 'guard_name' => 'api'])->assignRole($role);

        Permission::create(['name' => 'Buyer-vertical-channel.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer-vertical-channel.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer-vertical-channel.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Buyer-vertical-channel.delete', 'guard_name' => 'api'])->assignRole($role);

        Permission::create(['name' => 'Fake-lead.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Fake-lead.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Fake-lead.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Fake-lead.delete', 'guard_name' => 'api'])->assignRole($role);

        Permission::create(['name' => 'Ip-ban.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Ip-ban.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Ip-ban.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Ip-ban.delete', 'guard_name' => 'api'])->assignRole($role);
        

        Permission::create(['name' => 'Lead.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Lead.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Lead.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Lead.delete', 'guard_name' => 'api'])->assignRole($role);

        Permission::create(['name' => 'Live-lead.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Live-lead.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Live-lead.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Live-lead.delete', 'guard_name' => 'api'])->assignRole($role);

        Permission::create(['name' => 'Portfolio.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Portfolio.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Portfolio.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Portfolio.delete', 'guard_name' => 'api'])->assignRole($role);

        Permission::create(['name' => 'Provider.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Provider.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Provider.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Provider.delete', 'guard_name' => 'api'])->assignRole($role);

        Permission::create(['name' => 'Redirect-setting.create', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Redirect-setting.read', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Redirect-setting.update', 'guard_name' => 'api'])->assignRole($role);
        Permission::create(['name' => 'Redirect-setting.delete', 'guard_name' => 'api'])->assignRole($role);


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
