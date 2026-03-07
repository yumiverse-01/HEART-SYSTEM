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
        Schema::create('attendances', function (Blueprint $table){
            $table->id('attendance_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->cascadeOnDelete();
            $table->foreignId('beneficiary_id')->constrained('beneficiaries', 'beneficiary_id')->cascadeOnDelete();
            $table->enum('present', ['Present', 'Absent'])->default('Absent');
            $table->string('attendance_status')->nullable();
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users', 'user_id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
