<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PrinterModel;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PrintermodelFactory extends Factory
{
    protected $model = PrinterModel::class;
    protected $table = 'printerModels';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->bothify('???-####-?#'),
        ];
    }
}
