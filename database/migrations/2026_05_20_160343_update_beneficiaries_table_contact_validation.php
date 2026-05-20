<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Update contact_number field to enforce strict length limit (11 chars max)
     */
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            // Modify contact_number to enforce 11 character maximum
            $table->string('contact_number', 11)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            // Revert to standard string
            $table->string('contact_number')->nullable()->change();
        });
    }
};