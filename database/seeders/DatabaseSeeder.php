<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\{Assistant, User};
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
            'name'    => 'Test User',
            'email'   => 'test@example.com',
            'role_id' => RoleEnum::ADMIN,
        ]);

        User::factory()->create([
            'name'    => 'Test CTO',
            'email'   => 'test-cto@example.com',
            'role_id' => RoleEnum::CTO,
        ]);

        Assistant::factory(10)->create();
    }
}
