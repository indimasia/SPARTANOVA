<?php

namespace App\Livewire\Pasukan;

use Livewire\Component;
use App\Models\JobCampaign;
use App\Models\JobDetail;
use App\Models\JobParticipant;
use App\Enums\PlatformEnum;
use App\Enums\JobType;
use App\Enums\JobStatusEnum;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ApplyJob extends Component
{
    public $showModal = false;
    public $selectedJob;
    public $jobDetail;

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
            ->withCount('participants as participantCount')
            ->latest()
            ->get();

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

        $this->dispatch('success', 'Berhasil melamar pekerjaan.');
        $this->closeModal();
    }
}
