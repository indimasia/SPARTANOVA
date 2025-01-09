<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Regency;
use App\Models\Village;
use Livewire\Component;
use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use App\Models\SosialMediaAccount;
use App\Enums\PlatformEnum;

class RegisterPasukan extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $gender;
    public $date_of_birth;
    public $phone;
    public $village_kode;
    public $district_kode;
    public $regency_kode;
    public $province_kode;
    public $social_media = [];

    public $villages = [];
    public $districts = [];
    public $regencies = [];
    public $provinces = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'gender' => 'required|in:L,P',
        'date_of_birth' => 'required|date',
        'phone' => 'required|string|max:15',
        'village_kode' => 'required',
        'district_kode' => 'required',
        'regency_kode' => 'required',
        'province_kode' => 'required',
        'social_media.*' => ['nullable', 'string']
    ];

    public function mount()
    {
        $this->provinces = Province::all();
        foreach (PlatformEnum::cases() as $platform) {
            $this->social_media[$platform->value] = '';
        }
    }

    public function updatedProvinceKode($value)
    {
        $this->regencies = Regency::where('prov_kode', $value)->get();
        $this->districts = collect();
        $this->villages = collect();
        $this->regency_kode = null;
        $this->district_kode = null;
        $this->village_kode = null;
    }

    public function updatedRegencyKode($value)
    {
        $this->districts = District::where('regency_kode', $value)->get();
        $this->villages = collect();
        $this->district_kode = null;
        $this->village_kode = null;
    }

    public function updatedDistrictKode($value)
    {
        $this->villages = Village::where('district_kode', $value)->get();
        $this->village_kode = null;
    }

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'gender' => ['required', 'in:L,P'],
            'date_of_birth' => ['required', 'date'],
            'phone' => ['required', 'string', 'max:15'],
            'village_kode' => ['required'],
            'district_kode' => ['required'],
            'regency_kode' => ['required'],
            'province_kode' => ['required'],
            'social_media.*' => ['nullable', 'string']
        ]);

        $birthYear = (int) date('Y', strtotime($validated['date_of_birth']));

        $generationCategory = $this->determineGeneration($birthYear);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone' => $validated['phone'],
            'village_kode' => $validated['village_kode'],
            'district_kode' => $validated['district_kode'],
            'regency_kode' => $validated['regency_kode'],
            'province_kode' => $validated['province_kode'],
            'generation_category' => $generationCategory,
        ];

        $user = User::create($userData);
        event(new Registered($user));

        foreach ($validated['social_media'] as $platform => $account) {
            if (!empty($account)) {
                SosialMediaAccount::create([
                    'user_id' => $user->id,
                    'sosial_media' => $platform,
                    'account' => $account,
                ]);
            }
        }

        $user->assignRole('pasukan');

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register-pasukan', [
            'provinces' => $this->provinces,
            'regencies' => $this->regencies,
            'districts' => $this->districts,
            'villages' => $this->villages
        ])->layout('layouts.app');
    }

    private function determineGeneration(int $birthYear): string
    {
        if ($birthYear >= 1946 && $birthYear <= 1964) {
            return 'Baby Boomers';
        } elseif ($birthYear >= 1965 && $birthYear <= 1980) {
            return 'Gen X';
        } elseif ($birthYear >= 1981 && $birthYear <= 1996) {
            return 'Gen Y';
        } elseif ($birthYear >= 1997 && $birthYear <= 2012) {
            return 'Gen Z';
        } elseif ($birthYear >= 2013 && $birthYear <= 2025) {
            return 'Gen Alpha';
        }

        return 'Unknown';
    }
}
