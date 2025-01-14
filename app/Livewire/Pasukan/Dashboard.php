<?php

namespace App\Livewire\Pasukan;

use App\Models\JobParticipant;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        if ($user->hasRole('pasukan')) {
            $userId = $user->id;

            $recentActivities = Cache::get('user_activities_' . $userId, []);

            $totalJobs = JobParticipant::getTotalJobsByUser($userId);
            $pendingJobs = JobParticipant::getPendingJobsByUser($userId);
            $approvedJobs = JobParticipant::getApprovedJobsByUser($userId);

            $recentActivities = array_filter($recentActivities, function ($activity) use ($userId) {
                return isset($activity['user_id']) && $activity['user_id'] == $userId;
            });

            return view('livewire.pasukan.dashboard', compact('totalJobs', 'pendingJobs', 'approvedJobs', 'recentActivities'))
                ->layout('layouts.app');
        }

        return redirect()->route('dashboard');
    }
}
