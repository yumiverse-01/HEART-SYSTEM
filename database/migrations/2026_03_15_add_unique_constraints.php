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
        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['beneficiary_id', 'event_id'], 'unique_beneficiary_event_attendance');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->unique(['event_name', 'event_date'], 'unique_event_name_date');
        });

        Schema::table('event_service_records', function (Blueprint $table) {
            $table->unique(['beneficiary_id', 'event_id', 'service_date'], 'unique_service_per_beneficiary_event');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('unique_beneficiary_event_attendance');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique('unique_event_name_date');
        });

        Schema::table('event_service_records', function (Blueprint $table) {
            $table->dropUnique('unique_service_per_beneficiary_event');
        });
    }
};
