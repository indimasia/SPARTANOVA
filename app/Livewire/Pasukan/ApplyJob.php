<?php

namespace App\Livewire\Pasukan;

use App\Enums\JobType;
use App\Models\Regency;
use App\Models\Village;
use Livewire\Component;
use App\Models\District;
use App\Models\Province;
use App\Models\JobDetail;
use App\Enums\PlatformEnum;
use App\Models\JobCampaign;
use App\Enums\JobStatusEnum;
use App\Models\JobParticipant;
use App\Models\UserPerformance;
use Livewire\Attributes\Layout;
use App\Models\SosialMediaAccount;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class ApplyJob extends Component
{
    public $showModal = false;
    public $selectedJob;
    public $jobDetail;
    public $province;
    public $regency;
    public $district;
    public $village;

    // Search and filter properties
    public $search = '';
    public $selectedPlatform = '';
    public $selectedType = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedPlatform' => ['except' => ''],
        'selectedType' => ['except' => '']
    ];

    public function render()
{
    $user = Auth::user();

    $userSocialMediaPlatforms = SosialMediaAccount::where('user_id', $user->id)
        ->where('account', '!=', 'Tidak punya akun')
        ->pluck('sosial_media')
        ->toArray();

    $jobCampaigns = JobCampaign::query()
        ->when($this->search, function ($query) {
            $query->where('title', 'like', '%' . $this->search . '%');
        })
        ->when($this->selectedPlatform, function ($query) {
            $query->where('platform', $this->selectedPlatform);
        })
        ->when($this->selectedType, function ($query) {
            $query->where('type', $this->selectedType);
        })
        ->whereIn('platform', $userSocialMediaPlatforms)
        ->whereHas('jobDetail', function ($query) use ($user) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('specific_gender')
                    ->orWhere('specific_gender', $user->gender);
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('specific_generation')
                    ->orWhereJsonContains('specific_generation', $user->generation_category);
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('specific_province')
                    ->orWhereJsonContains('specific_province', $user->province_kode);
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('specific_regency')
                    ->orWhereJsonContains('specific_regency', $user->regency_kode);
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('specific_district')
                    ->orWhereJsonContains('specific_district', $user->district_kode);
            })
            ->where(function ($q) use ($user) {
                $q->whereNull('specific_village')
                    ->orWhereJsonContains('specific_village', $user->village_kode);
            })
            ->where(function ($q) use ($user) {
                if (!empty($user->interest)) {
                    $q->whereNull('specific_interest')
                        ->orWhere(function ($q2) use ($user) {
                            foreach ($user->interest as $interest) {
                                $q2->orWhereJsonContains('specific_interest', $interest);
                            }
                        });
                }
            });
        })
        ->withCount('participants as participantCount')
        ->latest()
        ->get()
        ->map(function ($job) {
            $job->instructions = Purifier::clean($job->instructions);
            return $job;
        });

    return view('livewire.pasukan.apply-job', [
        'jobCampaigns' => $jobCampaigns,
        'platforms' => PlatformEnum::cases(),
        'types' => JobType::cases()
    ])->layout('layouts.app');
}


    public function showJobDetail($jobId)
    {
        $this->selectedJob = JobCampaign::withCount('participants as participantCount')->find($jobId);
        $this->jobDetail = JobDetail::where('job_id', $jobId)->first();
        $this->province = Province::where('kode', $this->jobDetail->specific_province)->first();
        $this->regency = Regency::where('kode', $this->jobDetail->specific_regency)->first();
        $this->district = District::where('kode', $this->jobDetail->specific_district)->first();
        $this->village = Village::where('kode', $this->jobDetail->specific_village)->first();

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedJob = null;
        $this->jobDetail = null;
    }

    public function applyJob($jobId)
    {
        $job = JobCampaign::withCount('participants as participantCount')->find($jobId);

        if ($job->participantCount >= $job->quota) {
            session()->flash('error', 'Kuota pekerjaan sudah penuh.');
            return;
        }

        JobParticipant::create([
            'user_id' => Auth::id(),
            'job_id' => $jobId,
            'status' => JobStatusEnum::APPLIED->value,
            'reward' => $job->reward
        ]);

        // Create or update user performance
        $userPerformance = UserPerformance::firstOrNew(['user_id' => Auth::id()]);
        $userPerformance->user_id = Auth::id();
        $userPerformance->job_completed = 1;
        $userPerformance->total_reward = $job->reward;
        $userPerformance->save();

        $this->dispatch('success', 'Berhasil melamar pekerjaan.');
        $this->closeModal();
    }
}
