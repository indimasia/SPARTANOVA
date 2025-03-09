<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class WithdrawExport implements FromArray, WithStyles
{
    protected $rowCount = 0;

    public function array(): array
    {
        $data = [];

        // Ambil data withdraw yang masih pending
        $withdrawals = Transaction::where('type', 'withdrawal')
            ->where('status', 'pending')
            ->get();

        if ($withdrawals->isEmpty()) {
            return [['Tidak ada withdraw yang belum dikonfirmasi']];
        }

        // Tambahkan header tabel
        $data[] = ['TABEL: INFORMASI WITHDRAW'];
        $data[] = self::withdrawHeadings();

        // Tambahkan data withdraw ke dalam array
        foreach ($withdrawals as $withdraw) {
            $data[] = [
                $withdraw->user->name ?? '-',
                $withdraw->TopUpTransactions->nama_bank ?? '-',
                'Rp ' . number_format($withdraw->amount, 0, ',', '.'),
                $withdraw->in_the_name_of ?? '-',
                $withdraw->no_bank_account ?? '-',
                $withdraw->status ?? '-',
            ];
        }

        $this->rowCount = count($data);
        return $data;
    }

    private static function withdrawHeadings(): array
    {
        return [
            'Nama',
            'Jumlah',
            'Bank',
            'Nama Penerima',
            'Nomor Rekening',
            'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $headerRow = 2; // Baris header ada di baris ke-2
        $totalRows = $this->rowCount;

        // Atur lebar kolom agar menyesuaikan panjang teks
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Atur warna background header menjadi orange
        $sheet->getStyle("A{$headerRow}:F{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], // Teks putih
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFA500'], // Warna orange
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Tambahkan border ke seluruh tabel (mulai dari header ke bawah)
        $sheet->getStyle("A{$headerRow}:F{$totalRows}")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'], // Warna hitam
                    ],
                ],
            ]);

        return [];
    }
}
