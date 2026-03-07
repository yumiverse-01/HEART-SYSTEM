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
        Schema::create('staff_activity_logs', function (Blueprint $table) {
            $table->id('activity_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete();
            $table->string('activity_name')->nullable();
            $table->string('activity_type')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('timestamp');
            $table->text('activity_details')->nullable();
            $table->foreignId('provided_by')->nullable()->constrained('users', 'user_id')->cascadeOnDelete();
            $table->dateTime('service_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_activity_logs');
    }
};
