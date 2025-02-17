<?php

namespace App\Exports;

use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Models\JobDetail;
use App\Models\JobCampaign;
use App\Models\JobParticipant;
use Barryvdh\DomPDF\Facade\Pdf;

class JobPdfExport
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function download()
    {
        // Ambil data seperti yang dilakukan pada export Excel
        $job = JobCampaign::where('id', $this->id)
            ->select(['title', 'type', 'platform', 'quota', 'reward', 'status', 'start_date', 'end_date'])
            ->first();

        if (!$job) {
            return 'Misi tidak ditemukan';
        }
        $jobDetail = JobDetail::where('job_id', $this->id)->first();
        
        $provinces = Province::getProvinceName($jobDetail->specific_province ?? null);
        $regencies = Regency::getRegencyName($jobDetail->specific_regency ?? null);
        $districts = District::getDistrictName($jobDetail->specific_district ?? null);
        $villages = Village::getVillageName($jobDetail->specific_village ?? null);

        $data = [
            'job' => $job,
            'jobDetails' => JobDetail::where('job_id', $this->id)->first(),
            'jobParticipants' => JobParticipant::where('job_id', $this->id)->with('user')->get(),
            'jobHeadings' => self::jobHeadings(),
            'jobDetailHeadings' => self::jobDetailHeadings(),
            'jobParticipantHeadings' => self::jobParticipantHeadings(),
            'provinces' => $provinces,
            'regencies' => $regencies,
            'districts' => $districts,
            'villages' => $villages,
        ];

        // Load the view and pass the data
        $pdf = Pdf::loadView('exports.job_pdf', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $job->title . '.pdf');
    }

    private static function jobHeadings(): array
    {
        return [
            'Judul',
            'Tipe',
            'Platform',
            'Kuota',
            'Poin',
            'Tanggal Mulai',
            'Tanggal Selesai',
        ];
    }

    private static function jobDetailHeadings(): array
    {
        return [
            'Deskripsi',
            'URL Link',
            'Caption',
            'Gender',
            'Generasi',
            'Minat',
            'Provinsi',
            'Kabupaten',
            'Kecamatan',
            'Desa',
        ];
    }

    private static function jobParticipantHeadings(): array
    {
        return [
            'Nama',
            'Poin',
            'Tanggal Bergabung',
            'Gender',
            'Generasi',
        ];
    }
}
