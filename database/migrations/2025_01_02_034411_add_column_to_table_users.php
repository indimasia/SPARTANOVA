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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender',['L','P'])->after('password')->nullable();
            $table->date('date_of_birth')->after('gender')->nullable();
            $table->string('phone')->after('date_of_birth')->nullable();
            $table->string('village_kode')->after('phone')->nullable();
            $table->string('district_kode')->after('village_kode')->nullable();
            $table->string('regency_kode')->after('district_kode')->nullable()->default(null);
            $table->string('province_kode')->after('regency_kode')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('date_of_birth');
            $table->dropColumn('phone');
            $table->dropColumn('district_id');
            $table->dropColumn('village_id');
            $table->dropColumn('gender');
            $table->dropColumn('province_kode');
            $table->dropColumn('regency_kode');

        });
    }
};

