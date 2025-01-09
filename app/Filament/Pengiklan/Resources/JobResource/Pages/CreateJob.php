<?php

namespace App\Filament\Pengiklan\Resources\JobResource\Pages;

use App\Enums\PackageEnum;
use App\Filament\Pengiklan\Resources\JobResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if($data['package_rate'] != PackageEnum::LAINNYA->value){
             $data['quota'] = $data['package_rate'];
        }
        return $data;
    }
    protected function handleRecordCreation(array $data): Model
    {
        $jobData = static::getModel()::create($data);
        if (isset($data['jobDetail'])) {
            $jobDetail = new \App\Models\JobDetail();
            $jobDetail->job_id = $jobData->id;
            $jobDetail->image = $data['jobDetail']['image'] ?? null;
            $jobDetail->description = $data['jobDetail']['description'] ?? null;
            $jobDetail->specific_gender = $data['gender'] ?? null;
            $jobDetail->specific_generation = $data['generation'] ?? null;
            $jobDetail->specific_interest = $data['interest'] ?? null;
            $jobDetail->specific_province = $data['province'] ?? null;
            $jobDetail->specific_regency = $data['regency'] ?? null;
            $jobDetail->specific_district = $data['district'] ?? null;
            $jobDetail->specific_village = $data['village'] ?? null;
            $jobDetail->url_link = $data['jobDetail']['url_link'] ?? null;
            $jobDetail->save();
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
