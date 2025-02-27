<?php

namespace App\Livewire\Pasukan;

use Livewire\Component;
use App\Models\Annoucement;
use Livewire\Attributes\On;
use App\Models\JobParticipant;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\UserPerformance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
            // dd($recentActivities);
                $annoucements = Annoucement::whereIn('role', ['pasukan', 'both'])->get();

            $totalEarnings = UserPerformance::where('user_id', $userId)->pluck('total_reward')->first();

            $dataWithdraw = auth()->user()->unreadNotifications;
            return view('livewire.pasukan.dashboard', compact('totalJobs', 'pendingJobs', 'approvedJobs', 'recentActivities', 'totalEarnings', 'annoucements', 'dataWithdraw'))
                ->layout('layouts.app');
        }

        return redirect()->route('dashboard');
    }
}
