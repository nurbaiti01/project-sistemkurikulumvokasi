<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CapaianPembelajaranMatakuliah>
 */
class CapaianPembelajaranMatakuliahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code'=> 'CPMK-' . $this->faker->unique()->bothify('###'),
            'description' => $this->faker->sentence(25),
        ];
    }
}
