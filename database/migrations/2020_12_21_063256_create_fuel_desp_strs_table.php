<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelDespStrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_desp_strs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('storage_id');
            $table->unsignedBigInteger('despencer_id');

            $table->timestamps();
            $table->foreign('storage_id')->references('id')->on('fuel_station_storages');
            $table->foreign('despencer_id')->references('id')->on('fuel_despensers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fuel_desp_strs');
    }
}
