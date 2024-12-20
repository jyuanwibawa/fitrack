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
        Schema::create('latihan', function (Blueprint $table) {
            $table->increments('id_latihan');
            $table->string('nama_latihan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('latihan');
    }
};
