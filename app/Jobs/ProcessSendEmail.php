<?php

namespace App\Jobs;

use App\Mail\MyTestEmail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSendEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public function __construct(public User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // dd('saaaa');
        Mail::to($this->user->email)->send(new MyTestEmail($this->user));
        // Log::info('Berhasil dikirim ke: ' . $this->email);
    }
}
