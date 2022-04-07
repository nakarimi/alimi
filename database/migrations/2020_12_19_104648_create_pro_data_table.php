<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->string('title');
            $table->string('reference_no');
            $table->integer('pr_worth');
            $table->integer('deposit');
            $table->integer('tax');
            $table->integer('transit');
            $table->integer('others');
            $table->unsignedBigInteger('currency_id');
            $table->integer('total_price');
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('proposals');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pro_data');
    }
}
