<?php

namespace Database\Seeders;

use App\Models\Role;
use Database\Factories\RoleFactory;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::factory(5)->create();
    }
}
