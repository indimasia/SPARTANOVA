<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data Misi: {{ $job->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            color: #4a4a4a;
            border-bottom: 2px solid #f4a460;
            padding-bottom: 10px;
        }
        h2 {
            color: #f4a460;
            margin-top: 30px;
        }
        .info-section {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .table th {
            background-color: #f4a460;
            color: white;
        }
        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Export Data Misi: {{ $job->title }}</h1>
    </div>

    <!-- Job Information -->
    <div class="info-section">
        <h2>Informasi Misi</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Judul:</span> {{ $job->title }}
            </div>
            <div class="info-item">
                <span class="info-label">Tipe:</span> {{ $job->type->value ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Platform:</span> {{ $job->platform->value ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Kuota:</span> {{ $job->quota ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Poin:</span> {{ $job->reward ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Mulai:</span> {{ \Carbon\Carbon::parse($job->start_date)->format('d-m-Y') }}
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Selesai:</span> {{ \Carbon\Carbon::parse($job->end_date)->format('d-m-Y') }}
            </div>
        </div>
    </div>

    <!-- Job Details -->
    @if ($jobDetails)
    <div class="info-section">
        <h2>Detail Misi</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Deskripsi:</span> {{ $jobDetails->description ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">URL Link:</span> {{ $jobDetails->url_link ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Caption:</span> {{ $jobDetails->caption ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Gender Spesifik:</span> {{ $jobDetails->specific_gender ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Generasi Spesifik:</span> {{ implode(', ', $jobDetails->specific_generation ?? []) ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Minat Spesifik:</span> {{ implode(', ', $jobDetails->specific_interest ?? []) ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Provinsi Spesifik:</span> {{ implode(', ', $provinces ?? []) ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Kabupaten Spesifik:</span> {{ implode(', ', $regencies ?? []) ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Kecamatan Spesifik:</span> {{ implode(', ', $districts ?? []) ?? '-' }}
            </div>
            <div class="info-item">
                <span class="info-label">Desa Spesifik:</span> {{ implode(', ', $villages ?? []) ?? '-' }}
            </div>
        </div>
    </div>
    @endif

    <!-- Participants -->
    @if ($jobParticipants->isNotEmpty())
    <div class="info-section">
        <h2>Daftar Peserta Misi</h2>
        <table class="table">
            <thead>
                <tr>
                    @foreach ($jobParticipantHeadings as $heading)
                        <th>{{ $heading }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($jobParticipants as $participant)
                    <tr>
                        {{-- @dd($participant->user->sosialMediaAccounts); --}}
                        <td>{{ $participant->user->name ?? '-' }}</td>
                        <td>{{ $participant->lokasi['county'] ?? '-' }}</td>
                        <td>{{ $participant->filtered_social_account ?? '-' }}</td>
                        <td>
                            image
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</body>
</html>