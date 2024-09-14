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
        Schema::create('transaksi_transfer', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi');
            $table->integer('id_user');
            $table->foreign('id_user')->references('id')->on('user');
            $table->integer('id_bank_tujuan');
            $table->foreign('id_bank_tujuan')->references('id')->on('bank');
            $table->string('nomor_rekening_tujuan');
            $table->string('atas_nama_rekening_tujuan');
            $table->integer('id_rekening_admin');
            $table->foreign('id_rekening_admin')->references('id')->on('rekening_admin');
            $table->integer('nilai_transfer');
            $table->integer('kode_unik');
            $table->integer('biaya_admin')->default(0);
            $table->integer('total_transfer');
            $table->timestamp('berlaku_hingga');
            $table->enum('status', ['berhasil', 'tertunda', 'gagal'])->default('tertunda');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_transfer');
    }
};
