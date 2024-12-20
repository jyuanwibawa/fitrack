<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('langkah', function (Blueprint $table) {
            $table->increments('id_langkah');
            $table->unsignedInteger('id_latihan');
            $table->string('nama_step');
            $table->string('gambar_step')->nullable();
            $table->string('interval');
            $table->timestamps();
    
            $table->foreign('id_latihan')->references('id_latihan')->on('latihan')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('langkah');
    }
    
};
