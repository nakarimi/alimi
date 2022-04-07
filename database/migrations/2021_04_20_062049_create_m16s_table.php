<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateM16sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m16s', function (Blueprint $table) {
            $table->id();
            $table->string('m16_number');
            $table->string('sale_id');
            $table->string('sale_type');
            $table->string('delay_charge');
            $table->string('tax')->default(0);
            $table->string('deposit')->default(0);
            $table->longText('description')->nullable();    
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
        Schema::dropIfExists('m16s');
    }
}
