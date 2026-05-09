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
        // Drop description column from events table
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        // Drop remarks column from event_service_records table
        Schema::table('event_service_records', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back description column to events table
        Schema::table('events', function (Blueprint $table) {
            $table->text('description')->nullable()->after('location');
        });

        // Add back remarks column to event_service_records table
        Schema::table('event_service_records', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('treatment_given');
        });
    }
};
