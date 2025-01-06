<?php

namespace App\Livewire\Pasukan;

use Livewire\Component;
use App\Models\JobParticipant;
use Illuminate\Support\Facades\Auth;

class RiwayatPekerjaan extends Component
{
    public $jobHistory;
    public function mount()
    {
        $this->jobHistory = JobParticipant::with(['job', 'job.jobDetail'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.pasukan.riwayat-pekerjaan')->layout('layouts.app');
    }
}
