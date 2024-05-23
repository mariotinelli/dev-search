<?php

namespace Database\Factories;

use App\Models\Assistant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AssistantFactory extends Factory
{
    protected $model = Assistant::class;

    public function definition(): array
    {
        return [
            'cpf' => generateCpf(),
            'user_id' => User::factory(),
            'created_by' => User::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => fake()->boolean() ? Carbon::now() : null,
        ];
    }
}
