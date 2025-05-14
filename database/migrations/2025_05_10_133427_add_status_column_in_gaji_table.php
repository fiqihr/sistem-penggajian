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
    Schema::table('gaji', function (Blueprint $table) {
      $table->enum('status', ['belum', 'dikirim', 'diterima'])->after('total_gaji')->default('belum')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('gaji', function (Blueprint $table) {
      //
    });
  }
};
