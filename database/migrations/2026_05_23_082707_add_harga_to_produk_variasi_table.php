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
        Schema::table('produk_variasi', function (Blueprint $table) {
            // Nambahin kolom harga di tabel variasi
            $table->integer('harga')->default(0)->after('stok');
        });
    }

    public function down()
    {
        Schema::table('produk_variasi', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
};
