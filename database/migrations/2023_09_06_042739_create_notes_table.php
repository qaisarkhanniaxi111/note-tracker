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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->foreignId('clinician_id')->constrained('users');
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('error_type_id')->constrained();
            $table->tinyInteger('status');
            $table->text('status_reason')->nullable();
            $table->date('date_of_service');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
