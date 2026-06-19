<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('komplain_pesanan');
    }

    public function down(): void
    {
        // Tidak perlu recreate, fitur ini sudah tidak dipakai
    }
};