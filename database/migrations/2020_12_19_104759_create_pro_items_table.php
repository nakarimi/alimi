<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('proposal_id')->nullable();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('uom_equiv_id');
            $table->unsignedBigInteger('operation_id');
            $table->integer('ammount');
            $table->integer('unit_price');
            $table->integer('total_price');
            $table->integer('equivalent');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('proposal_id')->references('id')->on('proposals');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('unit_id')->references('id')->on('measurment_units');
            $table->foreign('uom_equiv_id')->references('id')->on('measurment_units');
            $table->foreign('operation_id')->references('id')->on('operations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pro_items');
    }
}
