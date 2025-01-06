<?php

namespace App\Enums;

enum UserInterestEnum: string
{
    case SPORT = 'Sport';
    case AGAMA = 'Agama';
    case MUSIK = 'Musik';
    case PEKERJAAN = 'Pekerjaan';
    case MASAK = 'Masak';
    case CINTA = 'Cinta';
    case SKINCARE = 'Skincare';
    case PENDIDIKAN = 'Pendidikan';
    case FILM = 'Film';
    case BISNIS = 'Bisnis';
    case SENI = 'Seni';
    case ORGANISASI = 'Organisasi';
    case FASHION = 'Fashion';
    case ELEKTRONIK = 'Elektronik';
    case OTOMOTIF = 'Otomotif';
    case SEPAK_BOLA = 'Sepak bola';
    case OUTDOOR = 'Outdoor';
    case BUKU = 'Buku';
    case TRAVEL = 'Travel';
    case HUKUM = 'Hukum';
    case POLITIK = 'Politik';
    case KURSUS = 'Kursus';
    case BELANJA = 'Belanja';
    case JUALAN = 'Jualan';
    case DIGITALISASI = 'Digitalisasi';
    case KULINER = 'Kuliner';
    case NONGKRONG = 'Nongkrong';

    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }
}
