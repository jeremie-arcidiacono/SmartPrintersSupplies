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
        Schema::create('printerModel_supply', function (Blueprint $table) {
            $table->foreignId('idPrinterModel')->constrained('printerModels', 'idPrinterModel');
            $table->foreignId('idSupply')->constrained('supplies', 'idSupply');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printerModel_supply');
    }
};
