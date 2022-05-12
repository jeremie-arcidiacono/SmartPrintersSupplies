<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printer_supply', function (Blueprint $table) {
            $table->foreignId('printer_idPrinter')->constrained('printers', 'idPrinter');
            $table->foreignId('supply_idSupply')->constrained('supplies', 'idSupply');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printer_supply');
    }
};
