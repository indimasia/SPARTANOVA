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
            if (Schema::hasColumn('job_campaigns', 'created_by')) {
                $table->dropForeign(['created_by']); // Hapus foreign key jika ada
                $table->dropColumn('created_by');   // Hapus kolom created_by
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_campaigns', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('id'); // Tambahkan kolom kembali
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null'); // Tambahkan foreign key
        });
    }
};
