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
use Illuminate\Support\Facades\File;
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
        static $attachmentsDelete = []; // Gunakan variabel static agar bisa digunakan di shutdown function
    
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
            ->map(function ($participant) use ($job, &$attachmentsDelete) { // Tambahkan referensi ke $attachmentsDelete
                
                // Ambil akun sosial media yang sesuai dengan platform job
                $filteredAccount = $participant->user->sosialMediaAccounts
                    ->firstWhere('sosial_media', $job->platform->value);
                
                $user = User::where('id', $participant->user_id)->first();
                $participant->lokasi = ($user->latitude && $user->longitude)
                    ? $this->getLocation($user->latitude, $user->longitude)
                    : '-';

                $participant->filtered_social_account = $filteredAccount ? $filteredAccount->account : '-';

                if ($participant->attachment) {
                    // Ambil file dari R2
                    $imageContent = Storage::disk('r2')->get($participant->attachment);

                    // Path penyimpanan di folder public/export/
                    $localPath = 'export/' . basename($participant->attachment);
                    $destinationPath = public_path($localPath);

                    // Pastikan folder 'export' ada di public/
                    if (!File::exists(public_path('export'))) {
                        File::makeDirectory(public_path('export'), 0755, true);
                    }
                    
                    // Simpan file ke public/
                    File::put($destinationPath, $imageContent);
                    
                    // Simpan path baru untuk digunakan di Blade
                    $participant->attachment_url = asset($localPath);
                    
                    $attachmentsDelete[] = $destinationPath;
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
    
        $response = response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $job->title . '.pdf');

        // Hapus file setelah PDF selesai dibuat
        register_shutdown_function(function () use (&$attachmentsDelete) { // Gunakan referensi agar bisa diakses
            foreach ($attachmentsDelete as $deleteAttachment) {
                if (File::exists($deleteAttachment)) {
                    File::delete($deleteAttachment);
                }
            }
        });
        
        return $response;
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
