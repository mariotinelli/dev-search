<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\{User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'role_id' => RoleEnum::ADMIN,
        ]);

        User::factory()->create([
            'name' => 'CTO',
            'email' => 'cto@example.com',
            'role_id' => RoleEnum::CTO,
        ]);

//        Assistant::factory(10)->create();
    }
}
