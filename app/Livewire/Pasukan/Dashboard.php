<?php

namespace App\Livewire\Pasukan;

use Livewire\Component;
use App\Models\JobParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;

class Dashboard extends Component
{
    #[On('updateLocation')]
    public function updateLocation($latitude, $longitude)
    {
        $user = Auth::user();

        // Update data lokasi pengguna
        $user->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
        ]);

        // Set flash message
        session()->flash('status', 'Lokasi berhasil diperbarui.');
    }


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
