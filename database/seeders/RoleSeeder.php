<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => RoleEnum::ADMIN->getLabel(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => RoleEnum::CTO->getLabel(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => RoleEnum::ASSISTANT->getLabel(),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
