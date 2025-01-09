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
        $jobDetail->image = $data['jobDetail']['image']?? $jobDetail->image;
        $jobDetail->description = $data['jobDetail']['description'] ?? $jobDetail->description;
        $jobDetail->url_link = $data['jobDetail']['url_link'] ?? $jobDetail->url_link;
        $jobDetail->save();
        // dd($record);
        $record->update($data);
        return $record;
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['package_rate'] = $data['quota'] > PackageEnum::TEN_THOUSAND->value ? PackageEnum::LAINNYA->value : $data['quota'];

        $jobDetail = JobDetail::where('job_id', $data['id'])->first();
        if ($jobDetail) {
            $data['jobDetail']['image'] = $jobDetail->image;
            $data['jobDetail']['description'] = $jobDetail->description;
            $data['jobDetail']['url_link'] = $jobDetail->url_link;
        } else {
            $data['jobDetail'] = [
                'image' => null,
                'description' => null,
                'url_link' => null,
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
