<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reward::create([
            'name' => 'ZONK',
            'description' => 'ZONK',
            'image' => null,
            'quantity' => null,
            'probability' => 1.00,
            'is_available' => 1,
        ]);
    }
}
