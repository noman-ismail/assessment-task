<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure roles exist before creating a user
        $this->call(RoleSeeder::class);

        // Create a user
        $user = User::firstOrCreate([
            'email' => 'superadmin@example.com'
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password') // Ensure you hash the password
        ]);

        // Assign the super-admin role to the user
        $user->assignRole('super-admin');
    }
}
