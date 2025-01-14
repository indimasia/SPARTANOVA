<?php

namespace App\Observers;

use App\Models\JobParticipant;
use Illuminate\Support\Facades\Cache;

class JobParticipantObserver
{
    public function updated(JobParticipant $jobParticipant)
    {
        if ($jobParticipant->isDirty('status')) {
            $activity = [
                'description' => "Status pekerjaan '{$jobParticipant->job->title}' diubah menjadi '{$jobParticipant->status}'.",
                'type' => $jobParticipant->status,
                'created_at' => now(),
                'user_id' => $jobParticipant->user_id,
            ];

            $activities = Cache::get('user_activities_' . $jobParticipant->user_id, []);
            array_unshift($activities, $activity);
            $endOfDay = now()->endOfDay();
            Cache::put('user_activities_' . $jobParticipant->user_id, $activities, $endOfDay);
        }
    }
}
