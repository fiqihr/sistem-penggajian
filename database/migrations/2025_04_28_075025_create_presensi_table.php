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
        Schema::create('presensi', function (Blueprint $table) {
            $table->bigIncrements('id_presensi');
            $table->string('bulan', 7);
            $table->unsignedBigInteger('id_guru');
            $table->integer('hadir');
            $table->integer('sakit');
            $table->integer('alpha');
            $table->timestamps();

            // foreign key
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_tabel');
    }
};
