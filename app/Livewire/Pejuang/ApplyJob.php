<?php

namespace App\Livewire\Pejuang;

use Livewire\Component;
use App\Models\JobDetail;
use App\Models\JobCampaign;
use App\Models\JobParticipant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ApplyJob extends Component
{
    public $jobCampaigns;
    public $showModal = false;
    public $selectedJob = null;
    public $jobDetail = null;

    public function mount()
    {
        $this->jobCampaigns = JobCampaign::where('status', 'publish')
            ->where('end_date', '>=', Carbon::now())
            ->where('quota', '>', 0)
            ->get();
    }

    public function showJobDetail($job_id)
    {
        $this->selectedJob = JobCampaign::find($job_id);
        $this->jobDetail = JobDetail::where('job_id', $job_id)->first();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedJob = null;
        $this->jobDetail = null;
    }

    public function applyJob($job_id)
    {
        try {
            $existingApplication = JobParticipant::where('user_id', Auth::id())
                ->where('job_id', $job_id)
                ->first();

            if ($existingApplication) {
                session()->flash('error', 'Anda sudah melamar untuk kampanye ini.');
                return;
            }

            $job = JobCampaign::find($job_id);

            if (!$job || $job->quota <= 0) {
                session()->flash('error', 'Kuota kampanye sudah penuh atau tidak tersedia.');
                return;
            }
            JobParticipant::create([
                'user_id' => Auth::id(),
                'job_id' => $job_id,
                'status' => 'pending',
                'reward' => $job->reward,
                'attachment' => '',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $job->decrement('quota');

            session()->flash('success', 'Berhasil melamar kampanye. Silakan tunggu persetujuan dari admin.');
            $this->closeModal();

            $this->mount();
        } catch (\Exception $e) {
            Log::error('Error in applyJob: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan. Silakan coba lagi nanti.');
        }
    }

    public function render()
    {
        return view('livewire.pejuang.apply-job')->layout('layouts.app');
    }
}
