<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeveloperSeeder extends Seeder
{
    public function run(): void
    {
        $developers = file_get_contents(database_path('seeders/jsons/developers.json'));

        $developers = json_decode($developers, true);

        DB::table('developers')->insert($developers);
    }
}
