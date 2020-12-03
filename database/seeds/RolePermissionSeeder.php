<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Define roles to seed
        $roles = ['Super Admin', 'Admin', 'user'];

        foreach ($roles as $role) {
            Role::findOrCreate($role,'api');
        }

        //Define permissions to seed
        //Brand Logo
        $permissions = [
            'Logo' => [
                'read','update'
            ],
            'User' => [
                'create','read','update','delete'
            ],
        ];

        foreach ($permissions as $subject => $actions) {
            foreach ($actions as $action) {
                Permission::findOrCreate("{$subject}.{$action}",'api');
            }
        }
    }
}
