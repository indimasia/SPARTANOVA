<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Regency;
use App\Models\Village;
use Livewire\Component;
use App\Models\District;
use App\Models\Province;
use App\Enums\UserStatusEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterPengiklan extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $gender;
    public $date_of_birth;
    public $phone;
    public $company;
    public $village_kode;
    public $district_kode;
    public $regency_kode;
    public $province_kode;
    public $latitude;
    public $longitude;

    public $villages = [];
    public $districts = [];
    public $regencies = [];
    public $provinces = [];
    protected $listeners = ['setLocation', 'register'];

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
        'company' => 'required|string|max:255',
        'latitude' => 'required',
        'longitude' => 'required',
    ];

    public function mount()
    {
        $this->provinces = Province::all();
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
                'company' => ['required', 'string', 'max:255'],
                'latitude' => ['required'],
                'longitude' => ['required'],
            ],[
                'name.required' => 'Nama harus diisi',
                'name.string' => 'Nama harus berupa string',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password harus terdiri dari setidaknya 6 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
                'gender.required' => 'Jenis kelamin harus diisi',
                'gender.in' => 'Jenis kelamin harus L atau P',
                'date_of_birth.required' => 'Tanggal lahir harus diisi',
                'date_of_birth.date' => 'Tanggal lahir harus berupa tanggal',
                'phone.required' => 'Nomor telepon harus diisi',
                'phone.string' => 'Nomor telepon harus berupa string',
                'phone.max' => 'Nomor telepon tidak boleh lebih dari 15 karakter',
                'village_kode.required' => 'Kelurahan harus diisi',
                'district_kode.required' => 'Kecamatan harus diisi',
                'regency_kode.required' => 'Kabupaten harus diisi',
                'province_kode.required' => 'Provinsi harus diisi',
                'company.required' => 'Nama perusahaan harus diisi',
                'company.string' => 'Nama perusahaan harus berupa string',
                'company.max' => 'Nama perusahaan tidak boleh lebih dari 255 karakter',
                'latitude.required' => 'Latitude harus diisi',
                'longitude.required' => 'Longitude harus diisi',
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

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = UserStatusEnum::PENDING->value;

        event(new Registered($user = User::create($validated)));

        $user->assignRole('pengiklan');

        $this->redirect(route('home', absolute: false), navigate: true);
    }

    public function setLocation($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function render()
    {
        return view('livewire.auth.register-pengiklan', [
            'provinces' => $this->provinces,
            'regencies' => $this->regencies,
            'districts' => $this->districts,
            'villages' => $this->villages
        ])
            ->layout('layouts.app');
    }
}
