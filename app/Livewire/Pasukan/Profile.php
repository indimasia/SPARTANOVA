<?php

namespace App\Livewire\Pasukan;

use App\Models\User;
use App\Models\Regency;
use App\Models\Village;
use Livewire\Component;
use App\Models\District;
use App\Models\Province;
use App\Enums\PlatformEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Profile extends Component
{
    public $user;
    public $name;
    public $email;
    public $bio;
    public $gender;
    public $date_of_birth;
    public $phone;
    public $contact_wa;
    public $interest;
    public $generation_category;
    public $village_kode;
    public $district_kode;
    public $regency_kode;
    public $province_kode;
    public $status;
    public $villages = [];
    public $districts = [];
    public $sosialMediaAccounts = [];
    public $regencies = [];
    public $provinces = [];

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

public function updateVillageKode($value)
{
    $this->village_kode = $value;
}


    public function mount()
{
    $this->user = Auth::user()->load('village', 'district', 'regency', 'province');
    $this->name = $this->user->name;
    $this->email = $this->user->email;
    $this->status = $this->user->status;
    $this->bio = $this->user->bio;
    $this->gender = $this->user->gender;
    $this->date_of_birth = $this->user->date_of_birth ? $this->user->date_of_birth->format('Y-m-d') : null;
    $this->phone = $this->user->phone;
    $this->contact_wa = $this->user->contact_wa;
    $this->interest = $this->user->interest;
    $this->generation_category = $this->user->generation_category;
    $this->province_kode = $this->user->province_kode;
    $this->regency_kode = $this->user->regency_kode;
    $this->district_kode = $this->user->district_kode;
    $this->village_kode = $this->user->village_kode;
    $this->sosialMediaAccounts = collect($this->user->sosialMediaAccounts)
        ->keyBy('sosial_media')
        ->map(fn($account) => $account['account'])
        ->toArray();
    // dd($this->sosialMediaAccounts);

    $this->provinces = Province::all();

    // Load data sesuai relasi
    $this->regencies = Regency::where('prov_kode', $this->province_kode)->get();
    $this->districts = District::where('regency_kode', $this->regency_kode)->get();
    $this->villages = Village::where('district_kode', $this->district_kode)->get();
}



    public function updateProfile()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'gender' => 'required|string|max:10',
                'date_of_birth' => 'required|date',
                'phone' => 'required|string|max:15',
                'contact_wa' => 'required|string|max:15',
                'generation_category' => 'required|string|max:255',
                'village_kode' => 'required|string|max:255',
                'district_kode' => 'required|string|max:255',
                'regency_kode' => 'required|string|max:255',
                'province_kode' => 'required|string|max:255',
                'interest' => 'required|array|min:10|max:15',
                'interest.*' => 'string',
                'sosialMediaAccounts' => 'required|array',
                'sosialMediaAccounts.*' => 'nullable|string|max:255',
            ],
            [
                'interest.required' => 'Minat wajib diisi.',
                'interest.*.string' => 'Minat harus berupa string.',
                'interest.*.array' => 'Minat harus berupa array.',
                'interest.min' => 'Minat harus memiliki setidaknya 10 karakter.',
                'interest.max' => 'Minat harus memiliki paling banyak 15 karakter.',
                'name.required' => 'Nama wajib diisi.',
                'name.string' => 'Nama harus berupa string.',
                'name.max' => 'Nama harus memiliki paling banyak 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email harus berupa alamat email yang valid.',
                'email.max' => 'Email harus memiliki paling banyak 255 karakter.',
                'gender.required' => 'Jenis kelamin wajib diisi.',
                'gender.string' => 'Jenis kelamin harus berupa string.',
                'gender.max' => 'Jenis kelamin harus memiliki paling banyak 10 karakter.',
                'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
                'date_of_birth.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
                'phone.required' => 'Nomor telepon wajib diisi.',
                'phone.string' => 'Nomor telepon harus berupa string.',
                'phone.max' => 'Nomor telepon harus memiliki paling banyak 15 karakter.',
                'contact_wa.required' => 'Kontak WA wajib diisi.',
                'contact_wa.string' => 'Kontak WA harus berupa string.',
                'contact_wa.max' => 'Kontak WA harus memiliki paling banyak 15 karakter.',
                'generation_category.required' => 'Kategori generasi wajib diisi.',
                'generation_category.string' => 'Kategori generasi harus berupa string.',
                'generation_category.max' => 'Kategori generasi harus memiliki paling banyak 255 karakter.',
                'village_kode.required' => 'Kode desa wajib diisi.',
                'village_kode.string' => 'Kode desa harus berupa string.',
                'village_kode.max' => 'Kode desa harus memiliki paling banyak 255 karakter.',
                'district_kode.required' => 'Kode kecamatan wajib diisi.',
                'district_kode.string' => 'Kode kecamatan harus berupa string.',
                'district_kode.max' => 'Kode kecamatan harus memiliki paling banyak 255 karakter.',
                'regency_kode.required' => 'Kode kabupaten wajib diisi.',
                'regency_kode.string' => 'Kode kabupaten harus berupa string.',
                'regency_kode.max' => 'Kode kabupaten harus memiliki paling banyak 255 karakter.',
                'province_kode.required' => 'Kode provinsi wajib diisi.',
                'province_kode.string' => 'Kode provinsi harus berupa string.',
                'province_kode.max' => 'Kode provinsi harus memiliki paling banyak 255 karakter.',
                'sosialMediaAccounts.required' => 'Sosial media wajib diisi.',
                'sosialMediaAccounts.array' => 'Sosial media harus berupa array.',
                'sosialMediaAccounts.*.string' => 'URL sosial media harus berupa string.',
                'sosialMediaAccounts.*.max' => 'URL sosial media harus memiliki paling banyak 255 karakter.',
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

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'bio' => $this->bio,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'phone' => $this->phone,
            'contact_wa' => $this->contact_wa,
            'interest' => $this->interest,
            'generation_category' => $this->generation_category,
            'village_kode' => $this->village_kode,
            'district_kode' => $this->district_kode,
            'regency_kode' => $this->regency_kode,
            'province_kode' => $this->province_kode,
        ]);

        foreach ($this->sosialMediaAccounts as $platform => $account) {
            $this->user->sosialMediaAccounts()
                ->updateOrCreate(
                    ['sosial_media' => $platform],
                    ['account' => $account]
                );
        }
        $this->dispatch('notification','Profile updated successfully.');
        return;
    }

    public function render()
    {
        return view('livewire.pasukan.profile')->layout('layouts.app');
    }
}
