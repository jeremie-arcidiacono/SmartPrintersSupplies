<?php

namespace Database\Seeders;

use App\Models\PrinterModel;
use App\Models\Printer;
use App\Models\Supply;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // check if app is in development mode
        if (app()->environment('local')) {
            User::factory()->create([
                'username' => 'e',
                'email' => 'e@e.e',
                'password' => bcrypt('123'),
            ]);
        }

        User::factory(3)->create();

        PrinterModel::factory(10)
            ->has(Printer::factory()->count(3))
            ->has(Supply::factory()->count(5))
            ->create();
    }
}
