<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => Role::ROLE_SUPERADMIN]);
        $roleGuest = Role::create(['name' => Role::ROLE_GUEST]);

        $admin = User::factory()->create([
            'name' => 'Jakub Vitásek',
            'email' => 'me@jvitasek.cz',
            'password' => bcrypt('secret'),
        ]);
        $admin->assignRole($roleAdmin);

        $guest = User::factory()->create([
            'name' => 'Test Testov',
            'email' => 'test@jvitasek.cz',
            'password' => bcrypt('secret'),
        ]);
        $guest->assignRole($roleGuest);

        $this->call(TableSeeder::class);
        $this->call(BookingSeeder::class);
    }
}
