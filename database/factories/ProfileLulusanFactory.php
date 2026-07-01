<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfileLulusan>
 */
class ProfileLulusanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'=> 'PL-' . $this->faker->unique()->bothify('###'),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->sentence(25),
        ];
    }
}
