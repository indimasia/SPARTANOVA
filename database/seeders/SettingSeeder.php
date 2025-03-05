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

        Setting::updateOrCreate(
            ['key_name' => 'Minimum Withdraw'],
            ['value' => '250']
        );

        Setting::updateOrCreate(
            ['key_name' => 'Company Name'],
            ['value' => 'PT Sinergi Mitra Mediatama']
        );

        Setting::updateOrCreate(
            ['key_name' => 'Email'],
            ['value' => 'eov.eventrue@gmail.com']
        );

        Setting::updateOrCreate(
            ['key_name' => 'Phone'],
            ['value' => '08999950006']
        );

        Setting::updateOrCreate(
            ['key_name' => 'Address'],
            ['value' => 'Semarang, Indonesia']
        );
        
        

    }
}
