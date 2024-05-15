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
        Schema::create('spatial_ref_sys', function (Blueprint $table) {

            $table->integer('srid')->primary();
            $table->string('auth_name', 256);
            $table->integer('auth_srid')->nullable();
            $table->string('srtext', 2048);
            $table->string('proj4text', 2048);
          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spatial_ref_sys');
    }
};
