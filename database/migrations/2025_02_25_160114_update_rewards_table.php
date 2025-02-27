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
        Schema::table('rewards', function (Blueprint $table) {
            // Menghapus kolom rarity
            $table->dropColumn('rarity');

            // Menghapus kolom status dan menambahkan kolom baru is_available (boolean)
            $table->dropColumn('status');
            $table->boolean('is_available')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            // Mengembalikan kolom rarity
            $table->enum('rarity', ['common', 'rare', 'epic', 'legendary']);

            // Mengembalikan kolom status dan menghapus is_available
            $table->dropColumn('is_available');
            $table->enum('status', ['available', 'out_of_stock', 'hidden'])->default('available');
        });
    }
};
