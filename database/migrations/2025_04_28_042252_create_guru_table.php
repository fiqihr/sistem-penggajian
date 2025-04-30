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
        Schema::create('guru', function (Blueprint $table) {
            $table->bigIncrements('id_guru');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_jabatan');
            $table->string('nig');
            $table->string('jenis_kelamin');
            $table->date('tanggal_masuk');
            $table->string('status');
            $table->string('photo');
            $table->timestamps();

            // foreign key
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_jabatan')->references('id_jabatan')->on('jabatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
