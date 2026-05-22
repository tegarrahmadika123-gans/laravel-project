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
        Schema::create('memberships', function (Blueprint $table) {

            $table->id();

            // user
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // paket
            $table->string('paket');

            // harga
            $table->integer('harga');

            // durasi hari
            $table->integer('durasi_hari');

            // metode pembayaran
            $table->string('payment_method');

            // bukti pembayaran
            $table->string('payment_proof')
                  ->nullable();

            // status pembayaran
            $table->enum('payment_status', [
                'pending',
                'paid',
                'rejected'
            ])->default('pending');

            // tanggal mulai aktif
            $table->timestamp('started_at')
                  ->nullable();

            // expired
            $table->timestamp('expired_at')
                  ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};