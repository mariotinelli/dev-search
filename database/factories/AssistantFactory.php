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
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'user_id' => User::factory(),
            'created_by' => User::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
