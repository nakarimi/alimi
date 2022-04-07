<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storages', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('manager');
            $table->string('phone');
            $table->text('address');
            $table->decimal('capacity');
            $table->unsignedBigInteger('oum_id');

            $table->timestamps();
            $table->foreign('oum_id')->references('id')->on('measurment_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storages');
    }
}
