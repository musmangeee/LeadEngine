<?php
namespace Database\Seeds;

use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class FakeUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        /*$user = User::create([
            'name' => 'warrence',
            'email' => 'warrence@gmail.com',
            'password' => bcrypt('0000'),
            'status' => 'active'
        ]);

        $user->assignRole('Admin');*/

        for ($i = 0; $i < 100; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('0000'),
                'status' => $faker->randomElement(['active', 'disabled'])
            ]);

            $user->assignRole($faker->randomElement(['Admin', 'User']));
        }
    }
}
