<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Printer>
 */
class PrinterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'model' => $this->faker->bothify('???-####-?#'),
            'serialNumber' => $this->faker->randomNumber(9, true),
            'cti' => $this->faker->randomNumber(6, true),
        ];
    }
}
