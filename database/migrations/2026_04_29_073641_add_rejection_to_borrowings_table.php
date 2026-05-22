<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {

            // 🔥 tambah status baru
            $table->enum('status', [
                'pending',
                'dipinjam',
                'kembali',
                'ditolak'
            ])->default('pending')->change();

            // 🔥 alasan penolakan
            $table->text('rejection_reason')
                  ->nullable()
                  ->after('status');

        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {

            $table->dropColumn('rejection_reason');

        });
    }
};