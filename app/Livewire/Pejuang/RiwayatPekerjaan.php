<?php

namespace App\Livewire\Pejuang;

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
        return view('livewire.pejuang.riwayat-pekerjaan')->layout('layouts.app');
    }
}
