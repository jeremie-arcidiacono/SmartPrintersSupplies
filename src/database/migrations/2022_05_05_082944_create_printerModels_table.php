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
        Schema::create('printerModels', function (Blueprint $table) {
            $table->bigIncrements("idPrinterModel");
            $table->string('brand', 20);
            $table->string('name', 60)->unique();
            $table->timestamp("created_at")->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printerModels');
    }
};
