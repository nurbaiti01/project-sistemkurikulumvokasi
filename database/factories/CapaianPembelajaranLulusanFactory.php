<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CapaianPembelajaranLulusan>
 */
class CapaianPembelajaranLulusanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'=> 'CPL-' . $this->faker->unique()->bothify('###'),
            'description' => $this->faker->sentence(25),
        ];
    }
}
