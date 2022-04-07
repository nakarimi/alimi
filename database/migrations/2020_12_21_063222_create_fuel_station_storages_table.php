<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelStationStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_station_storages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('station_id');
            $table->string('name');
            $table->string('supervisor');
            $table->decimal('capacity');
            $table->unsignedBigInteger('oum_id');

            $table->timestamps();
            $table->foreign('oum_id')->references('id')->on('measurment_units');
            $table->foreign('station_id')->references('id')->on('fuel_stations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fuel_station_storages');
    }
}
