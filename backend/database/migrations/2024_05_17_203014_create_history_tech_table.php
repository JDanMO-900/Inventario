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
        Schema::create('history_tech', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('history_change_id')->nullable();
            $table->foreign('history_change_id')->references('id')->on('history_change');
            $table->unsignedBigInteger('user_tech_id')->nullable();
            $table->foreign('user_tech_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_tech');
    }
};
