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
            // Drop the existing foreign key constraint
            $table->dropColumn('job_id');
            // Add the new foreign key constraint
            $table->foreignId('job_id')->constrained('job_campaigns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_details', function (Blueprint $table) {
            $table->dropColumn('job_id');
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
        });
    }
};
