<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('visitors', function (Blueprint $table) {
        $table->id();
        $table->string('ip_address'); // Untuk simpan IP visitor
        $table->text('user_agent')->nullable(); // Untuk simpan info browser/perangkat
        $table->timestamps(); // Ini otomatis membuat kolom 'created_at' dan 'updated_at'
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
