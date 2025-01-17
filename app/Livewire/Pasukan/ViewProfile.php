<?php

namespace App\Livewire\Pasukan;

use App\Models\Regency;
use App\Models\Village;
use Livewire\Component;
use App\Models\District;
use App\Models\JobParticipant;
use App\Models\Province;
use App\Models\UserPerformance;
use Illuminate\Support\Facades\Auth;

class ViewProfile extends Component
{
    public $user;
    public $name;
    public $role;
    public $email;
    public $bio;
    public $gender;
    public $date_of_birth;
    public $phone;
    public $contact_wa;
    public $interest;
    public $generation_category;
    public $village_nama;
    public $district_nama;
    public $regency_nama;
    public $province_nama;
    public $facebookAccounts;
    public $instagramAccounts;
    public $youtubeAccounts;
    public $tiktokAccounts;
    public $twitterAccounts;
    public $googleAccounts;
    public $job_done;
    public $reward;
    public $created_at;
    public $updated_at;
    public $status;
    public $villages = [];
    public $districts = [];
    public $regencies = [];
    public $provinces = [];

    public function mount()
    {
        $this->user = Auth::user()->load('village', 'district', 'regency', 'province', 'sosialMediaAccounts');
        $this->name = $this->user->name;
        $this->role = $this->user->roles->pluck('name')->first();
        $this->email = $this->user->email;
        $this->status = $this->user->status;
        $this->bio = $this->user->bio;
        $this->gender = $this->user->gender;
        $this->date_of_birth = $this->user->date_of_birth ? $this->user->date_of_birth->format('Y-m-d') : null;
        $this->phone = $this->user->phone;
        $this->contact_wa = $this->user->contact_wa;
        $this->interest = $this->user->interest;
        $this->generation_category = $this->user->generation_category;
        $this->province_nama = $this->user->province->nama;
        $this->regency_nama = $this->user->regency->nama;
        $this->district_nama = $this->user->district->nama;
        $this->village_nama = $this->user->village->nama;
        $this->facebookAccounts = $this->user->sosialMediaAccounts->where('user_id', $this->user->id)->where('sosial_media', 'Facebook')->pluck('account')->first();
        $this->instagramAccounts = $this->user->sosialMediaAccounts->where('user_id', $this->user->id)->where('sosial_media', 'Instagram')->pluck('account')->first();
        $this->youtubeAccounts = $this->user->sosialMediaAccounts->where('user_id', $this->user->id)->where('sosial_media', 'Youtube')->pluck('account')->first();
        $this->tiktokAccounts = $this->user->sosialMediaAccounts->where('user_id', $this->user->id)->where('sosial_media', 'TikTok')->pluck('account')->first();
        $this->twitterAccounts = $this->user->sosialMediaAccounts->where('user_id', $this->user->id)->where('sosial_media', 'Twitter')->pluck('account')->first();
        $this->googleAccounts = $this->user->sosialMediaAccounts->where('user_id', $this->user->id)->where('sosial_media', 'Google')->pluck('account')->first();
        $this->created_at = $this->user->created_at->format('d F Y');
        $this->updated_at = $this->user->updated_at->format('d F Y');
        $this->job_done = UserPerformance::where('user_id', $this->user->id)->pluck('job_completed')->first();
        $this->reward = UserPerformance::where('user_id', $this->user->id)->pluck('total_reward')->first();
    }

    public function handleAccountClick($platform)
    {
        switch ($platform) {
            case 'Facebook':
                $account = $this->facebookAccounts;
                break;
            case 'Instagram':
                $account = $this->instagramAccounts;
                break;
            case 'Youtube':
                $account = $this->youtubeAccounts;
                break;
            case 'TikTok':
                $account = $this->tiktokAccounts;
                break;
            case 'Twitter':
                $account = $this->twitterAccounts;
                break;
            case 'Google':
                $account = $this->googleAccounts;
                break;
            default:
                $account = null;
                break;
        }

        if ($account) {
            return redirect()->to($account);
        } else {
            session()->flash('error', 'Account not found.');
        }
    }

    public function render()
    {
        return view('livewire.pasukan.view-profile')->layout('layouts.app');
    }
}