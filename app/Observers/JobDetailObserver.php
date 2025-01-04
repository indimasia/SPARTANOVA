<?php

namespace App\Observers;

use App\Models\JobDetail;

class JobDetailObserver
{
    /**
     * Handle the JobDetail "created" event.
     */
    public function created(JobDetail $jobDetail): void
    {
        //
    }

    /**
     * Handle the JobDetail "updated" event.
     */
    public function updated(JobDetail $jobDetail): void
    {
        
    }
    public function updating(JobDetail $jobDetail): void
    {
        if ($jobDetail->isDirty('image')) {
            $oldImagePath = $jobDetail->getOriginal('image');
            if ($oldImagePath) {
                \Storage::disk('public')->delete($oldImagePath);
            }
        }
    }

    /**
     * Handle the JobDetail "deleted" event.
     */
    public function deleted(JobDetail $jobDetail): void
    {
        //
    }

    /**
     * Handle the JobDetail "restored" event.
     */
    public function restored(JobDetail $jobDetail): void
    {
        //
    }

    /**
     * Handle the JobDetail "force deleted" event.
     */
    public function forceDeleted(JobDetail $jobDetail): void
    {
        //
    }
}
