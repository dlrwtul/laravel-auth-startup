<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les utilisateurs
        $user = User::create([
            'username' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt("passer"),
        ]);
        $user->assignRole(Roles::SUPER_ADMIN->value);

        $user = User::create([
            'username' => 'Simple Admin',
            'email' => 'user@example.com',
            'password' => bcrypt("passer"),
        ]);
        $user->assignRole(Roles::ADMIN->value);
    }
}
