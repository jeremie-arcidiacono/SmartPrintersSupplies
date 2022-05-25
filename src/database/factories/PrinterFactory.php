<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Printer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Printer>
 */
class PrinterFactory extends Factory
{
    protected $model = Printer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'serialNumber' => $this->faker->randomNumber(9, true),
            'cti' => $this->faker->randomNumber(6, true),
        ];
    }
}
