<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add time_started and time_ended fields to events table
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add time fields after event_date
            $table->time('time_started')->nullable()->after('event_date');
            $table->time('time_ended')->nullable()->after('time_started');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['time_started', 'time_ended']);
        });
    }
};