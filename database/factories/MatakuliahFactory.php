<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matakuliah>
 */
class MatakuliahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'=> 'MK-' . $this->faker->unique()->bothify('###'),
            'name' => $this->faker->sentence(3),
            'sks' => $this->faker->randomNumber(1),
            'semester' => $this->faker->randomNumber(1),
            'description' => $this->faker->sentence(25),
        ];
    }
}
