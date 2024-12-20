<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGambarLatihanColumnInLatihansTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('latihans', function (Blueprint $table) {
            // Ubah tipe kolom `gambar_latihan` menjadi TEXT
            $table->text('gambar_latihan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('latihans', function (Blueprint $table) {
            // Kembalikan kolom `gambar_latihan` menjadi VARCHAR dengan panjang tertentu
            $table->string('gambar_latihan', 255)->change();
        });
    }
}
