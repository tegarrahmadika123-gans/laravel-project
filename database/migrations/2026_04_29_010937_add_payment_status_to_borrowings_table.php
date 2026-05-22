<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {

            $table->enum('payment_status', ['belum_lunas', 'lunas'])
                  ->default('belum_lunas')
                  ->after('denda_tambahan');

        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {

            $table->dropColumn('payment_status');

        });
    }
};