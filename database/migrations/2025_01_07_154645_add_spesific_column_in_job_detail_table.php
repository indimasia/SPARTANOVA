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
            $table->string('specific_gender')->after('description')->nullable();
            $table->string('specific_generation')->after('specific_gender')->nullable();
            $table->json('specific_interest')->after('specific_generation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_details', function (Blueprint $table) {
            $table->dropColumn('specific_gender');
            $table->dropColumn('specific_generation');
            $table->dropColumn('specific_interest');
        });
    }
};
