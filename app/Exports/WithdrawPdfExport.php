<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Models\JobDetail;
use App\Models\JobCampaign;
use App\Models\Transaction;
use App\Models\JobParticipant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WithdrawPdfExport
{
    protected $id;

    public function __construct()
    {

    }

    public function download()
    {
        $pdf = PDF::loadView('exports.withdraw', [
            'withdraws' => Transaction::where('type', 'withdrawal')->where('status', 'pending')->get()
        ]);

        $response = response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'withdraw_list_' . now()->format('Ymd_His') . '.pdf');

        return $response;
    }
}
