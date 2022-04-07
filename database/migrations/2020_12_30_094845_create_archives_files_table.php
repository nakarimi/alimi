<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivesFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archive_id');
            $table->foreign('archive_id')->references('id')->on('archives');
            $table->string('origname');
            $table->string('path');
            $table->string('newname');
            $table->string('mime');
            $table->string('caption');
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
        Schema::dropIfExists('archives_files');
    }
}
