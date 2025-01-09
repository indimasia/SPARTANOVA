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
        Schema::table('job_campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('job_campaigns', 'due_date')) {
                $table->dropColumn('due_date');
            }
            $table->dropColumn('reward');
            $table->integer('reward')->nullable(); 
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('instructions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_campaigns', function (Blueprint $table) {
            $table->decimal('reward', 8, 2);
            $table->date('due_date');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            
        });
    }
};
