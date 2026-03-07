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
        Schema::create('event_service_records', function (Blueprint $table) {
            $table->id('service_id');
            $table->foreignId('event_id')->constrained('events', 'event_id')->cascadeOnDelete();
            $table->foreignId('beneficiary_id')->constrained('beneficiaries', 'beneficiary_id')->cascadeOnDelete();
            $table->string('service_type')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment_given')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('provided_by')->nullable()->constrained('users', 'user_id')->cascadeOnDelete();
            $table->dateTime('service_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_service_records');
    }
};
