<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    public static function extractText($imagePath)
    {
        return (new TesseractOCR($imagePath))
            ->lang('eng') // Gunakan bahasa Inggris
            ->run();
    }

    public static function extractNumber($imagePath)
    {
        $text = self::extractText($imagePath);

        // Cari angka dalam teks yang diekstrak
        preg_match('/\d+/', str_replace(',', '', $text), $matches);

        return $matches[0] ?? null;
    }
}
