<?php

namespace App\Enums;

enum UserInterestEnum: string
{
    case PERTANIAN = 'Pertanian';
    case FINANCE = 'Finance (Keuangan)';
    case PROPERTY = 'Property';
    case KESEHATAN = 'Kesehatan';
    case HERBAL = 'Herbal';
    case KESEHATAN_MENTAL = 'Kesehatan Mental';
    case AGAMA = 'Agama';
    case SPIRITUAL = 'Spiritual';
    case SPORT = 'Sport (Olahraga)';
    case MUSIK = 'Musik';
    case FILM = 'Film';
    case PEKERJAAN = 'Pekerjaan';
    case MASAK = 'Masak (Kuliner)';
    case MAKANAN = 'Makanan';
    case CINTA_DAN_HUBUNGAN = 'Cinta dan Hubungan';
    case SKINCARE_DAN_KECANTIKAN = 'Skincare dan Kecantikan';
    case PENDIDIKAN = 'Pendidikan';
    case KURSUS_DAN_PELATIHAN = 'Kursus dan Pelatihan';
    case BISNIS = 'Bisnis';
    case SENI = 'Seni';
    case ORGANISASI = 'Organisasi';
    case FASHION = 'Fashion';
    case ELEKTRONIK = 'Elektronik';
    case OTOMOTIF = 'Otomotif';
    case SEPAKBOLA = 'Sepakbola';
    case PETUALANGAN_OUTDOOR = 'Petualangan Outdoor';
    case BUKU_DAN_LITERASI = 'Buku dan Literasi';
    case TRAVEL_DAN_PERJALANAN = 'Travel dan Perjalanan';
    case HUKUM = 'Hukum';
    case POLITIK = 'Politik';
    case BELANJA = 'Belanja';
    case JUALAN_DAN_E_COMMERCE = 'Jualan dan E-commerce';
    case DIGITALISASI = 'Digitalisasi';
    case PARENTING_DAN_ANAK = 'Parenting dan Anak';
    case GAMING_DAN_E_SPORTS = 'Gaming dan E-Sports';
    case TEKNOLOGI_INFORMASI_CODING = 'Teknologi Informasi / Coding';
    case LIFESTYLE = 'Lifestyle';
    case KOMUNITAS_DAN_SOSIAL = 'Komunitas dan Sosial';
    case HEWAN_PELIHARAAN = 'Hewan Peliharaan';
    case FOTOGRAFI_DAN_VIDEOGRAFI = 'Fotografi dan Videografi';
    case TRAVEL_VLOGGER = 'Travel Vlogger';
    case STARTUP_DAN_ENTREPRENEUR = 'Startup dan Entrepreneur';
    case USAHA_DI_RUMAH = 'Usaha di Rumah';
    case HOROR_DAN_MISTERI = 'Horor dan Misteri';

    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }
}
