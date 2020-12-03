<?php
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->first_name = 'Warrence';
        $user->last_name = 'Lim';
        $user->email = 'warrence@gmail.com';
        $user->timezone = 'Asia/Kuching';
        $user->password = bcrypt('warrence');
        $user->status = 'active';
        $user->save();
        $user->assignRole('Super Admin');

        $user = new \App\User();
        $user->first_name = 'Nathan';
        $user->last_name = 'Jacobs';
        $user->email = 'nathan@smtpdispatch.com';
        $user->timezone = 'UTC';
        $user->password = bcrypt('nathan');
        $user->status = 'active';
        $user->assignRole('Super Admin');
        $user->save();
    }
}
