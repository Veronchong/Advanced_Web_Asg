<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'species' => $this->faker->randomElement(['dog', 'cat', 'bird', 'rabbit']),
            'breed' => $this->faker->word,
            'age' => $this->faker->numberBetween(1, 15),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['available', 'pending', 'adopted']),
            'user_id' => User::factory(),
        ];
    }
}