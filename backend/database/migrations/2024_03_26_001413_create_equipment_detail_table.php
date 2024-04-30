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
        Schema::create('equipment_detail', function (Blueprint $table) {
            $table->id();
            $table->float('attribute')->nullable();
            $table->unsignedBigInteger('equipment_id');
            $table->foreign('equipment_id')->references('id')->on('equipment')->nullable();
            
            $table->unsignedBigInteger('technical_description_id')->nullable();
            $table->foreign('technical_description_id')->references('id')->on('technical_description');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_detail');
    }
};
