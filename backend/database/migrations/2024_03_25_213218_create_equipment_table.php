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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id()->autoIncrement();

            $table->string('number_active');
            $table->string('number_internal_active');
            $table->string('model');
            $table->string('serial_number');
            $table->date('adquisition_date');
            $table->string('invoice_number');
            $table->boolean('availability');
            
            $table->unsignedBigInteger('equipment_state_id');
            $table->foreign('equipment_state_id')->references('id')->on('equipment_state');
           


            $table->unsignedBigInteger('equipment_type_id');
            $table->foreign('equipment_type_id')->references('id')->on('equipment_type');
            
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brand');

            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('provider');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
