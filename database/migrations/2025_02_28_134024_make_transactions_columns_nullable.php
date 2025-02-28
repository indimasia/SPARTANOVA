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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('in_the_name_of')->nullable()->change();
            $table->string('no_bank_account', 20)->nullable()->change();
            $table->string('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('in_the_name_of')->nullable(false)->change();
            $table->string('no_bank_account', 20)->nullable(false)->change();
            $table->string('description')->nullable(false)->change();
        });
    }
};
