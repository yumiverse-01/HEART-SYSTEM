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
            // drop old present column if exists
            if (Schema::hasColumn('attendances', 'present')) {
                $table->dropColumn('present');
            }

            // change attendance_status to enum if not already
            if (Schema::hasColumn('attendances', 'attendance_status')) {
                // cannot directly change type in sqlite, but in mysql it's okay via modify
                $table->enum('attendance_status', ['Present', 'Absent'])->default('Absent')->change();
            } else {
                $table->enum('attendance_status', ['Present', 'Absent'])->default('Absent')->after('beneficiary_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'attendance_status')) {
                $table->string('attendance_status')->nullable()->change();
            }
            if (! Schema::hasColumn('attendances', 'present')) {
                $table->enum('present', ['Present', 'Absent'])->default('Absent')->after('beneficiary_id');
            }
        });
    }
};
