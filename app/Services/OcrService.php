<?php

namespace App\Services;

use CURLFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    public static function extractText($imagePath)
    {  
       
        try {
            $apiKey = env('OCR_API_KEY');
            $url = "https://api.ocr.space/parse/image";
           
            $response = Http::attach(
                'file',
                file_get_contents($imagePath),
                basename($imagePath)
            )->post($url, [
                'apikey' => $apiKey,
                'language' => 'eng',
                'isOverlayRequired' => "false",
                'isTable' => "false",
            ]);
        
            Log::debug($response->json()['ParsedResults'][0]['ParsedText']);
            return $response->json()['ParsedResults'][0]['ParsedText'];

        } catch (\Exception $e) {
            Log::info("err " . $e->getMessage());
            throw $e;
        }
        
    }

    public static function extractNumber($imagePath)
    {
        Log::info("RESPONSE===========");
        $text = self::extractText($imagePath);

        // Log::info(json_encode($text,JSON_PRETTY_PRINT));
        dd($text);
        // Cari angka dalam teks yang diekstrak
        preg_match('/\d+/', str_replace(',', '', $text), $matches);

        return $matches[0] ?? null;
    }
}
