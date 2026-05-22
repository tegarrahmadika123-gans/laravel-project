<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            // Kolom untuk kode verifikasi saat mau mengembalikan buku
            $table->string('return_verification_code')->nullable()->after('verification_code');
            
            // Kolom untuk denda tambahan (rusak/hilang)
            $table->integer('denda_tambahan')->default(0)->after('denda');
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['return_verification_code', 'denda_tambahan']);
        });
    }
};