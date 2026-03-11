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
        Schema::table('beneficiaries', function (Blueprint $table) {
            // add new fields
            if (! Schema::hasColumn('beneficiaries', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (! Schema::hasColumn('beneficiaries', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('middle_name');
            }
            if (Schema::hasColumn('beneficiaries', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
            if (Schema::hasColumn('beneficiaries', 'contact_info')) {
                $table->dropColumn('contact_info');
            }
            if (Schema::hasColumn('beneficiaries', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('beneficiaries', 'gender')) {
                $table->renameColumn('gender', 'sex');
            }
            if (! Schema::hasColumn('beneficiaries', 'contact_number')) {
                $table->string('contact_number', 20)->nullable()->after('address');
            }
            if (! Schema::hasColumn('beneficiaries', 'guardian_name')) {
                $table->string('guardian_name')->nullable()->after('contact_number');
            }
            if (! Schema::hasColumn('beneficiaries', 'date_registered')) {
                $table->date('date_registered')->nullable()->after('guardian_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            if (Schema::hasColumn('beneficiaries', 'middle_name')) {
                $table->dropColumn('middle_name');
            }
            if (Schema::hasColumn('beneficiaries', 'birth_date')) {
                $table->dropColumn('birth_date');
            }
            if (Schema::hasColumn('beneficiaries', 'sex')) {
                $table->renameColumn('sex', 'gender');
            }
            if (Schema::hasColumn('beneficiaries', 'contact_number')) {
                $table->dropColumn('contact_number');
            }
            if (Schema::hasColumn('beneficiaries', 'guardian_name')) {
                $table->dropColumn('guardian_name');
            }
            if (Schema::hasColumn('beneficiaries', 'date_registered')) {
                $table->dropColumn('date_registered');
            }
            // restore dropped columns
            if (! Schema::hasColumn('beneficiaries', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (! Schema::hasColumn('beneficiaries', 'contact_info')) {
                $table->string('contact_info')->nullable();
            }
            if (! Schema::hasColumn('beneficiaries', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }
};
