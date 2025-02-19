<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Models\JobDetail;
use App\Models\JobCampaign;
use App\Models\JobParticipant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

        $jobParticipants = JobParticipant::where('job_id', $this->id)
            ->with(['user.sosialMediaAccounts'])
            ->get()
            ->map(function ($participant) use ($job) {
                // Ambil akun sosial media yang sesuai dengan platform job
                $filteredAccount = $participant->user->sosialMediaAccounts
                    ->firstWhere('sosial_media', $job->platform->value);
                $user = User::where('id', $participant->user_id)->first();
                if ($user->latitude && $user->longitude) {
                    $lokasi = $this->getLocation($user->latitude, $user->longitude);
                } else {
                    $lokasi = '-';
                }
                $participant->lokasi = $lokasi;

                // Tambahkan akun sosial media yang difilter ke dalam object user
                $participant->filtered_social_account = $filteredAccount ? $filteredAccount->account : '-';

                // Tambahkan URL gambar attachment
                if ($participant->attachment) {
                    $participant->attachment_url = Storage::disk('r2')->url("{$participant->attachment}");
                } else {
                    $participant->attachment_url = null;
                }

                return $participant;
            });


        $data = [
            'job' => $job,
            'jobDetails' => JobDetail::where('job_id', $this->id)->first(),
            'jobParticipants' => $jobParticipants,
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

    public function getLocation($latitude, $longitude)
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1";

            $response = Http::withHeaders([
                'User-Agent' => config('app.name'), // atau gunakan nama aplikasi Anda
            ])->get($url);
        } catch (\Exception $e) {
            return null;
        }

        if ($response->successful()) {
            $data = $response->json();

            return [
                'county' => $data['address']['county'] ?? null,
            ];
        }

        return null;
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
            'Lokasi',
            'Username',
            'Image',
        ];
    }
}
