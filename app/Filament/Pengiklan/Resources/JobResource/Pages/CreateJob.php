<?php

namespace App\Filament\Pengiklan\Resources\JobResource\Pages;

use Filament\Actions;
use App\Enums\JobType;
use App\Enums\PackageEnum;
use App\Models\PackageRate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Pengiklan\Resources\JobResource;
use App\Models\ConversionRate;
use App\Models\Wallet;

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
        if($data['type'] == JobType::SELLING->value){
            $data['is_multiple'] = true;
        }
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

        
        $incrementPercentage = 0;
        if (!empty($data['gender'])) $incrementPercentage += 10;
        if (!empty($data['generation'])) $incrementPercentage += 10;
        if (!empty($data['interest'])) $incrementPercentage += 10;
        
        if (!empty($data['province_kode']) || !empty($data['regency_kode']) || !empty($data['district_kode']) || !empty($data['village_kode'])) {
            $incrementPercentage += 10;
        }
        $packageRate = PackageRate::where('type', $data['type'])->pluck('price')->first();
        $totalPackageRate = $packageRate * $data['quota'];

        
        $point = Wallet::where('user_id', Auth::id())->first();
        $point->total_points -= $totalPackageRate;
        $point->save();

        $imagePath = session('temporary_image_path');
        if ($imagePath) {
            // Hapus file dari disk R2
            Storage::disk('r2')->delete($imagePath);
            
            // Hapus session
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
