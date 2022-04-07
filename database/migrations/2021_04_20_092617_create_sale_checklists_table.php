<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_checklists', function (Blueprint $table) {
            $table->id();
            $table->boolean('official_steps')->nullable();
            $table->boolean('mof_gov')->nullable();
            $table->boolean('bank_operations')->nullable();
            $table->boolean('receive_money')->nullable();
            $table->boolean('get_documents')->nullable();
            $table->string('sale_id');
            $table->string('sale_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_checklists');
    }
}
