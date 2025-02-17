<?php

namespace App\Exports;

use App\Enums\JobType;
use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use App\Models\JobDetail;
use App\Models\JobCampaign;
use App\Models\JobParticipant;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JobExport implements FromArray, WithStyles, WithEvents
{
    protected $id;
    protected $rowCount = 0;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function array(): array
    {
        $data = [];

        // First table - Job Information
        $job = JobCampaign::where('id', $this->id)
            ->select(['title', 'type', 'platform', 'quota', 'reward', 'start_date', 'end_date'])
            ->first();

        if (!$job) {
            return [['Misi tidak ditemukan']];
        }

        $data[] = ['TABEL: INFORMASI MISI'];
        $data[] = self::jobHeadings();
        $data[] = [
            $job->title ?? '-',
            $job->type->value ?? '-',
            $job->platform->value ?? '-',
            $job->quota ?? '-',
            $job->reward ?? '-',
            $this->formatDate($job->start_date),
            $this->formatDate($job->end_date),
        ];

        // Add spacing
        $data[] = [''];
        $data[] = [''];

        // Second table - Job Details
        $jobDetail = JobDetail::where('job_id', $this->id)->first();
        // dd($jobDetail->specific_province, $jobDetail->specific_regency, $jobDetail->specific_district, $jobDetail->specific_village);
        $provinces = Province::getProvinceName($jobDetail->specific_province ?? null);
        $regencies = Regency::getRegencyName($jobDetail->specific_regency ?? null);
        $districts = District::getDistrictName($jobDetail->specific_district ?? null);
        $villages = Village::getVillageName($jobDetail->specific_village ?? null);

        
        if ($jobDetail) {
            $data[] = ['TABEL: DETAIL MISI'];
            $data[] = self::jobDetailHeadings();
            $data[] = [
                $jobDetail->description ?? '-',
                $jobDetail->url_link ?? '-',
                $jobDetail->caption ?? '-',
                $jobDetail->specific_gender ?? '-',
                implode(', ', $jobDetail->specific_generation) ?? '-',
                implode(', ', $jobDetail->specific_interest) ?? '-',
                implode(', ', $provinces ?? []) ?? '-',
                implode(', ', $regencies ?? []) ?? '-',
                implode(', ', $districts ?? []) ?? '-',
                implode(', ', $villages ?? []) ?? '-',
            ];
        }

        // Add spacing
        $data[] = [''];
        $data[] = [''];

        // Third table - Participants
        $jobParticipants = JobParticipant::where('job_id', $this->id)->with('user')->get();

        if ($jobParticipants->isNotEmpty()) {
            $data[] = ['TABEL: DAFTAR PESERTA MISI'];
            $data[] = self::jobParticipantHeadings();

            foreach ($jobParticipants as $participant) {
                $data[] = [
                    $participant->user->name ?? '-',
                    $participant->reward ?? '-',
                    $participant->user->gender ?? '-',
                    $participant->user->generation_category ?? '-',
                ];
            }
        }

        $this->rowCount = count($data);
        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $tableHeaderStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F4A460'], // Orange color for headers
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $tableTitleStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        return [
            1 => $tableTitleStyle,
            2 => $tableHeaderStyle,
            6 => $tableTitleStyle,
            7 => $tableHeaderStyle,
            11 => $tableTitleStyle,
            12 => $tableHeaderStyle,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Set column widths
                $sheet->getColumnDimension('A')->setWidth(30);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(20);
                $sheet->getColumnDimension('J')->setWidth(20);
                $sheet->getColumnDimension('K')->setWidth(20);

                // Set row height
                $sheet->getDefaultRowDimension()->setRowHeight(25);

                // Apply borders and text wrapping to all used cells
                $lastColumn = $sheet->getHighestColumn();
                $lastRow = $sheet->getHighestRow();
                
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'wrapText' => true,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                ]);

                // Merge title cells
                $sheet->mergeCells('A1:H1'); // First table title
                $sheet->mergeCells('A6:K6'); // Second table title
                $sheet->mergeCells('A11:D11'); // Third table title

                // Center align all titles
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('A11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            },
        ];
    }

    private function formatDate($date)
    {
        return $date ? date('d-m-Y', strtotime($date)) : '-';
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
            'Gender',
            'Generasi',
        ];
    }
}

