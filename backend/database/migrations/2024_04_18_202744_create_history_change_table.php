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
        Schema::create('history_change', function (Blueprint $table) {
            $table->id();

            $table->text('description')->nullable();
            $table->integer('quantity_out')->nullable();
            $table->integer('quantity_in')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
 


            $table->unsignedBigInteger('type_action_id');
            $table->foreign('type_action_id')->references('id')->on('type_action');

            $table->unsignedBigInteger('equipment_id');
            $table->foreign('equipment_id')->references('id')->on('equipment');

            $table->unsignedBigInteger('equipment_used_in_id')->nullable();
            $table->foreign('equipment_used_in_id')->references('id')->on('equipment');

            

            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('process_state');

            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('location');

            $table->unsignedBigInteger('dependency_id');
            $table->foreign('dependency_id')->references('id')->on('dependency');

            

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_change');
    }
};
