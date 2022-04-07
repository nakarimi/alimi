<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->string('name');
            $table->unsignedBigInteger('uom_id');
            $table->unsignedBigInteger('uom_equiv_id');
            $table->integer('equivalent');
            $table->text('description');
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('item_types');
            $table->foreign('uom_id')->references('id')->on('measurment_units');
            $table->foreign('uom_equiv_id')->references('id')->on('measurment_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
