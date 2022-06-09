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
        Schema::create('printers', function (Blueprint $table) {
            $table->bigIncrements("idPrinter");
            $table->string("room")->nullable();
            $table->string('serialNumber', 100)->unique();
            $table->integer('cti')->length(6)->unique();
            $table->timestamp("created_at")->useCurrent();
            $table->softDeletes();

            $table->foreignId('printer_model_idPrinterModel')->constrained('printerModels', 'idPrinterModel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printers');
    }
};
