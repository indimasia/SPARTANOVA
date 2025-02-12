<?php

namespace App\Filament\Resources\JobInAdminResource\Pages;

use Filament\Actions;
use App\Models\PackageRate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\JobInAdminResource;

class CreateJobInAdmin extends CreateRecord
{
    protected static string $resource = JobInAdminResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        $data['is_verified'] = 1;
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['reward'] = $data['reward'];
        $jobData = static::getModel()::create($data);
        if (isset($data['jobDetail'])) {
            $jobDetail = new \App\Models\JobDetail();
            $jobDetail->job_id = $jobData->id;
            $jobDetail->image = $data['jobDetail']['image'] ?? null;
            $jobDetail->description = $data['jobDetail']['description'] ?? null;
            $jobDetail->specific_gender = $data['gender'] ?? null;
            $jobDetail->specific_generation = $data['generation'] ?? null;
            $jobDetail->specific_interest = $data['interest'] ?? null;
            $jobDetail->specific_province = $data['province_kode'] ?? null;
            $jobDetail->specific_regency = $data['regency_kode'] ?? null;
            $jobDetail->specific_district = $data['district_kode'] ?? null;
            $jobDetail->specific_village = $data['village_kode'] ?? null;
            $jobDetail->url_link = $data['jobDetail']['url_link'] ?? null;
            $jobDetail->caption = $data['jobDetail']['caption'] ?? null;
            $jobDetail->save();
        }

        $imagePath = session('temporary_image_path');
        if ($imagePath) {
            // Hapus file dari disk R2
            Storage::disk('r2')->delete($imagePath);
            
            // Hapus session
            session()->forget('temporary_image_path');
        }


        return $jobData;
    }
}
