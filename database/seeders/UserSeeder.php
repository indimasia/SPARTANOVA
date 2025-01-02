<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as ModelsRole;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $adminRole = ModelsRole::create(['name' => 'admin']);
        $pengiklanRole = ModelsRole::create(['name' => 'pengiklan']);
        $pejuangRole = ModelsRole::create(['name' => 'pejuang']);

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'gender' => 'L',
            'date_of_birth' => '1990-01-01',
            'phone' => '1234567890',
            'province_kode' => '01',
            'regency_kode' => '0101',
            'district_kode' => '010101',
            'village_kode' => '01010101',
        ]);
        $admin->assignRole($adminRole);

        // Create Pengiklan User
        $user = User::create([
            'name' => 'Pengiklan User',
            'email' => 'pengiklan@gmail.com',
            'password' => Hash::make('12345678'),
            'gender' => 'P',
            'date_of_birth' => '1992-02-02',
            'phone' => '0987654321',
            'province_kode' => '01',
            'regency_kode' => '0101',
            'district_kode' => '010101',
            'village_kode' => '01010101',
        ]);
        $user->assignRole($pengiklanRole);

        // Create Pejuang User
        $user = User::create([
            'name' => 'Pejuang User',
            'email' => 'pejuang@gmail.com',
            'password' => Hash::make('12345678'),
            'gender' => 'L',
            'date_of_birth' => '1995-03-03',
            'phone' => '1122334455',
            'province_kode' => '01',
            'regency_kode' => '0101',
            'district_kode' => '010101',
            'village_kode' => '01010101',
        ]);
        $user->assignRole($pejuangRole);
    }
}
