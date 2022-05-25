<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Supply;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supply>
 */
class SupplyFactory extends Factory
{
    protected $model = Supply::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => $this->faker->bothify('???-####-?#'),
            'quantity' => $this->faker->numberBetween(0, 500)
        ];
    }
}
