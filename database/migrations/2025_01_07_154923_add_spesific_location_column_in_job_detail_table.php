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
        Schema::table('job_details', function (Blueprint $table) {
            $table->json('specific_province')->after('specific_interest')->nullable();
            $table->json('specific_regency')->after('specific_province')->nullable();
            $table->json('specific_district')->after('specific_regency')->nullable();
            $table->json('specific_village')->after('specific_district')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_details', function (Blueprint $table) {
            $table->dropColumn('specific_province');
            $table->dropColumn('specific_regency');
            $table->dropColumn('specific_district');
            $table->dropColumn('specific_village');
        });
    }
};
