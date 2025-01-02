<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('job_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('platform');
            $table->integer('quota');
            $table->decimal('reward', 8, 2);
            $table->string('status');
            $table->date('due_date');
            $table->boolean('is_multiple')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('job_campaigns');
    }
}
