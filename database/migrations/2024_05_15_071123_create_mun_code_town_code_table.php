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
        Schema::create('mun_code_town_code', function (Blueprint $table) {
            $table->id();
            $table->multiPolygon('geom');
            $table->integer('fid')->nullable();
            $table->integer('objectid')->nullable();
            $table->string('county');
            $table->string('name');
            $table->string('gnis')->nullable();
            $table->string('mun_code');
            $table->string('town_code', 10);
            $table->double('ctr_lat');
            $table->double('ctr_lon');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mun_code_town_code');
    }
};
