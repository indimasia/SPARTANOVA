<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Regency;
use App\Models\Village;
use Livewire\Component;
use App\Models\District;
use App\Models\Province;
use App\Enums\PlatformEnum;
use App\Enums\UserStatusEnum;
use App\Models\SosialMediaAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

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
    public $latitude;
    public $longitude;
    public $social_media = [];

    public $villages = [];
    public $districts = [];
    public $regencies = [];
    public $provinces = [];
    protected $listeners = ['setLocation'];

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
        'social_media.*' => ['nullable', 'string'],
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
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
        try {
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
                'social_media.*' => ['nullable', 'string'],
                'latitude' => ['required', 'numeric'],
                'longitude' => ['required', 'numeric'],
                'social_media.*' => ['required', 'string'],
            ], [
                'name.required' => 'Nama harus diisi.',
                'name.regex' => 'Nama hanya boleh mengandung huruf dan spasi.',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
                'email.required' => 'Email harus diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password harus diisi.',
                'password.confirmed' => 'Password tidak cocok.',
                'password.min' => 'Password harus terdiri dari setidaknya 6 karakter.',
                'gender.required' => 'Jenis kelamin harus dipilih.',
                'gender.in' => 'Jenis kelamin harus L atau P.',
                'date_of_birth.required' => 'Tanggal lahir harus diisi.',
                'phone.required' => 'Nomor telepon harus diisi.',
                'phone.max' => 'Nomor telepon tidak boleh lebih dari 15 karakter.',
                'village_kode.required' => 'Kode desa harus diisi.',
                'district_kode.required' => 'Kode kecamatan harus diisi.',
                'regency_kode.required' => 'Kode kabupaten harus diisi.',
                'province_kode.required' => 'Kode provinsi harus diisi.',
                'latitude.required' => 'Latitude harus diisi.',
                'longitude.required' => 'Longitude harus diisi.',
                'latitude.numeric' => 'Latitude harus berupa angka.',
                'longitude.numeric' => 'Longitude harus berupa angka.',
                'social_media.*.required' => 'Akun sosial media harus diisi.',
                'social_media.*.string' => 'Akun sosial media harus berupa string.',
            ]);
        } catch (ValidationException $e) {
            $errorField = array_key_first($e->validator->errors()->toArray());
            $this->js(<<<JS
                const input = document.getElementById('$errorField');
                if (input) {
                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    input.focus();
                }
            JS);
            throw $e;
        }

        $birthYear = (int) date('Y', strtotime($validated['date_of_birth']));
        $validated['status'] = UserStatusEnum::PENDING->value;

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
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
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

        $this->redirect(route('home', absolute: false), navigate: true);
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

    public function setLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
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
