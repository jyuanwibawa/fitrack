<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

        public function up()
        {
            Schema::table('latihan', function (Blueprint $table) {
                $table->string('gambar_latihan')->nullable()->after('nama_latihan');
            });
        }
    
        /**
         * Reverse the migrations.
         */
        public function down()
        {
            Schema::table('latihan', function (Blueprint $table) {
                $table->dropColumn('gambar_latihan');
            });
        }
    };
    

