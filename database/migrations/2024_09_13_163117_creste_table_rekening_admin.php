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
        Schema::create('rekening_admin', function (Blueprint $table) {
            $table->id();
            $table->foreign('id_bank')->references('id')->on('bank');
            $table->string('nomor_rekening');
            $table->string('atas_nama_rekening');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_admin');
    }
};
