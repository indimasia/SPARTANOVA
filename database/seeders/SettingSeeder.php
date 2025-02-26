<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Setting::updateOrCreate(
            ['key_name' => 'Mini Game'],
            ['value' => 'on']
        );

        Setting::updateOrCreate(
            ['key_name' => 'Poin Game'],
            ['value' => '100']
        );
    }
}
