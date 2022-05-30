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
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements("idEvent");
            $table->foreignId('idUser_author')->constrained('users', 'idUser');

            $table->enum('action', ['info', 'create', 'delete', 'changeAmount']);
            $table->string('comment', 255)->nullable();
            $table->integer('amount')->nullable();
            
            $table->timestamp("created_at")->useCurrent();

            $table->foreignId('idPrinter_target')->nullable()->constrained('printers', 'idPrinter');
            $table->foreignId('idSupply_target')->nullable()->constrained('supplies', 'idSupply');
            $table->foreignId('idPrinterModel_target')->nullable()->constrained('printerModels', 'idPrinterModel');
            $table->foreignId('idUser_target')->nullable()->constrained('users', 'idUser');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
