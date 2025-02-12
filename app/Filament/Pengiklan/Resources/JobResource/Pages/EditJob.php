<?php

namespace App\Filament\Pengiklan\Resources\JobResource\Pages;

use App\Enums\PackageEnum;
use App\Filament\Pengiklan\Resources\JobResource;
use App\Models\JobDetail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditJob extends EditRecord
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
{
    $jobDetail = JobDetail::where('job_id', $this->record->id)->first() ?? new JobDetail();
    $jobDetail->job_id = $this->record->id;

    // Cek apakah ada gambar baru
    if (isset($data['jobDetail']['image']) && $data['jobDetail']['image']) {
        // Menyimpan gambar ke R2 Storage dan mendapatkan path yang benar
        $image = $data['jobDetail']['image'];
        $userId = auth()->user()->id;
        $imageName = $image;  // Ambil nama file asli
        
        // Menyimpan path gambar ke database
        $jobDetail->image = $image;
        
        // Menyimpan gambar secara otomatis ke session
        session()->put('temporary_image_path', $image);
    }

    // Update data lainnya
    $jobDetail->description = $data['jobDetail']['description'] ?? $jobDetail->description;
    $jobDetail->url_link = $data['jobDetail']['url_link'] ?? $jobDetail->url_link;
    $jobDetail->specific_gender = $data['specific_gender'] ?? false ? ($data['gender'] ?? null) : null;
    $jobDetail->specific_generation = $data['specific_generation'] ?? false ? ($data['generation'] ?? null) : null;
    $jobDetail->specific_interest = $data['specific_interest'] ?? false ? ($data['interest'] ?? null) : null;

    if ($data['specific_location'] ?? false) {
        $jobDetail->specific_province = $data['province_kode'] ?? $jobDetail->specific_province;
        $jobDetail->specific_regency = isset($data['all_regency']) ? (!$data['all_regency'] ? ($data['regency_kode'] ?? null) : null) : null;
        $jobDetail->specific_district = isset($data['all_district']) ? (!$data['all_district'] ? ($data['district_kode'] ?? null) : null) : null;
        $jobDetail->specific_village = isset($data['all_village']) ? (!$data['all_village'] ? ($data['village_kode'] ?? null) : null) : null;
    } else {
        $jobDetail->specific_province = null;
        $jobDetail->specific_regency = null;
        $jobDetail->specific_district = null;
        $jobDetail->specific_village = null;
    }

    // Simpan data
    $jobDetail->save();
    $record->update($data);

    return $record;
}
    protected function mutateFormDataBeforeFill(array $data): array
    {
        
        $data['package_rate'] = $data['quota'] > PackageEnum::TEN_THOUSAND->value ? PackageEnum::LAINNYA->value : $data['quota'];
        
        $jobDetail = JobDetail::where('job_id', $data['id'])->first();
        if($jobDetail && $jobDetail->specific_gender){
            $data['gender'] = $jobDetail->specific_gender;
            $data['specific_gender'] =true;
        }
        if($jobDetail && $jobDetail->specific_generation){
            $data['generation'] = $jobDetail->specific_generation;
            $data['specific_generation'] =true;
        }
        if($jobDetail && $jobDetail->specific_interest){
            $data['interest'] = $jobDetail->specific_interest;
            $data['specific_interest'] =true;
        }
        if($jobDetail && $jobDetail->specific_province){
            $data['province_kode'] = $jobDetail->specific_province;
            $data['specific_location'] =true;
        }else{
            $data['specific_location'] =false;
        }
        if($jobDetail && $jobDetail->specific_regency){
            $data['regency_kode'] = $jobDetail->specific_regency;
            $data['all_regency'] = false;
        }else{
            $data['all_regency'] = true;
        }
        if($jobDetail && $jobDetail->specific_district){
            $data['district_kode'] = $jobDetail->specific_district;
            $data['all_district'] = false;
        }else{
            $data['all_district'] = true;
        }
        if($jobDetail && $jobDetail->specific_village){
            $data['village_kode'] = $jobDetail->specific_village;
            $data['all_village'] = false;
        }else{
            $data['all_village'] = true;
        }
        if ($jobDetail) {
            $data['jobDetail']['image'] = $jobDetail->image;
            $data['jobDetail']['description'] = $jobDetail->description;
            $data['jobDetail']['url_link'] = $jobDetail->url_link;
            $data['jobDetail']['caption'] = $jobDetail->caption;
        } else {
            $data['jobDetail'] = [
                'image' => null,
                'description' => null,
                'url_link' => null,
                'caption' => null,
            ];
        }

        return $data;
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
