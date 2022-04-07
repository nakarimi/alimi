<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_records', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('type_id');
            $table->string('source');
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('item_id');
            $table->decimal('increment');
            $table->decimal('decrement');
            $table->integer('uom_id');
            $table->decimal('increment_equiv');
            $table->decimal('decrement_equiv');
            $table->unsignedBigInteger('uom_equiv_id');
            $table->decimal('density');
            $table->unsignedBigInteger('operation_id');
            $table->decimal('item_cost');
            $table->decimal('total_cost');
            $table->string('remark');
            $table->timestamps();

            $table->foreign('operation_id')->references('id')->on('operations');
            $table->foreign('item_id')->references('id')->on('items');
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
        Schema::dropIfExists('stock_records');
    }
}
