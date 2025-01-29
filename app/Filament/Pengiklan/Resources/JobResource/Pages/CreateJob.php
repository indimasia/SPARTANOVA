<?php

namespace App\Filament\Pengiklan\Resources\JobResource\Pages;

use Filament\Actions;
use App\Enums\PackageEnum;
use App\Models\PackageRate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Pengiklan\Resources\JobResource;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if($data['package_rate'] != PackageEnum::LAINNYA->value){
             $data['quota'] = $data['package_rate'];
        }
        $data['created_by'] = Auth::id();
        return $data;
    }
    protected function handleRecordCreation(array $data): Model
    {
        $data['reward'] = PackageRate::where('type', $data['type'])->pluck('reward')->first();
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
            $fullPath = public_path('storage/images/' . basename($imagePath));
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            session()->forget('temporary_image_path');
        }

        return $jobData;
    }

    protected function getFormActions(): array
    {
        return [
            // $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record->getKey()]);
    }
}
