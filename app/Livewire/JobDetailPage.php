<?php

namespace App\Livewire;

use App\Models\Regency;
use App\Models\Village;
use Livewire\Component;
use App\Models\District;
use App\Models\Province;
use App\Models\JobDetail;
use App\Models\JobCampaign;
use App\Enums\JobStatusEnum;
use App\Models\JobParticipant;
use App\Models\UserPerformance;
use Illuminate\Support\Facades\Auth;

class JobDetailPage extends Component
{
    public $selectedJob;
    public $jobDetail;
    public $showModal = false;
    public $province;
    public $regency;
    public $district;
    public $village;

    public function mount($jobId)
    {
        $this->selectedJob = JobCampaign::withCount('participants as participantCount')->find($jobId);
        $this->jobDetail = JobDetail::where('job_id', $jobId)->first();
        $this->province = Province::where('kode', $this->jobDetail->specific_province)->first();
        $this->regency = Regency::where('kode', $this->jobDetail->specific_regency)->first();
        $this->district = District::where('kode', $this->jobDetail->specific_district)->first();
        $this->village = Village::where('kode', $this->jobDetail->specific_village)->first();
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
        return redirect()->route('job.detail', $jobId);

    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedJob = null;
        $this->jobDetail = null;
    }

    public function render()
    {
        return view('livewire.job-detail-page')->layout('layouts.app');
    }
}
