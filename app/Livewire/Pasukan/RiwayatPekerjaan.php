<?php

namespace App\Livewire\Pasukan;

use Livewire\Component;
use App\Enums\JobStatusEnum;
use Livewire\WithFileUploads;
use App\Models\JobParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatPekerjaan extends Component
{
    use WithFileUploads;
    public $attachment;
    public $jobHistory;
    public $selectedJobHistory;
    public $showModal = false;
    public $dropdownVisible = false;
    protected $listeners = ['closeDropdown' => 'closeDropdown'];
    public $viewAttachmentModal = false;
    public $viewAttachmentPath;
    public $status;

    public function toggleDropdown()
    {
        $this->dropdownVisible = !$this->dropdownVisible;
    }

    public function closeDropdown()
    {
        $this->dropdownVisible = false;
    }

    public function mount()
    {
        $this->getData();
    }

    public function getData()
    {
        $this->jobHistory = JobParticipant::with(['job', 'job.jobDetail', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function showUploadModal($historyId)
    {
        $this->attachment = null;
        $this->selectedJobHistory = $historyId;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function uploadBukti()
{
    $this->validate([
        'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ], [
        'attachment.required' => 'File bukti harus diunggah.',
        'attachment.file' => 'Harap unggah file yang valid.',
        'attachment.mimes' => 'File harus berupa: jpg, jpeg, png, pdf.',
        'attachment.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
    ]);

    $jobParticipant = JobParticipant::find($this->selectedJobHistory);

    if (!$jobParticipant) {
        session()->flash('error', 'Data tidak ditemukan.');
        return;
    }

    $userId = auth()->id();
    $fileName = time() . '_' . $this->attachment->getClientOriginalName();
    $path = "pasukan/attachment/{$userId}/{$fileName}";

    // Simpan ke Cloudflare R2
    $this->attachment->storeAs('pasukan/attachment/'.$userId, $fileName, 'r2');

    // Update database
    $jobParticipant->update([
        'attachment' => $path,
        'status' => JobStatusEnum::REPORTED->value,
    ]);

    $this->showModal = false;
    session()->flash('message', 'Bukti berhasil diupload!');
    $this->getData();
}


    public function showAttachmentModal($historyId)
    {
        $jobParticipant = JobParticipant::find($historyId);

        if ($jobParticipant && $jobParticipant->attachment) {
            $this->viewAttachmentPath = asset($jobParticipant->attachment);
            $this->status = $jobParticipant->status;
            $this->selectedJobHistory = $historyId;
            $this->viewAttachmentModal = true;
        } else {
            session()->flash('message', 'Bukti tidak ditemukan!');
        }
    }

    public function updateAttachment()
{
    $this->validate([
        'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ], [
        'attachment.required' => 'File bukti harus diunggah.',
        'attachment.file' => 'Harap unggah file yang valid.',
        'attachment.mimes' => 'File harus berupa: jpg, jpeg, png, pdf.',
        'attachment.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
    ]);

    $jobParticipant = JobParticipant::find($this->selectedJobHistory);

    if (!$jobParticipant) {
        session()->flash('message', 'Data tidak ditemukan!');
        return;
    }

    $userId = auth()->id();
    $directory = "pasukan/attachment/{$userId}";
    if ($jobParticipant->attachment) {
        \Storage::disk('r2')->delete($jobParticipant->attachment);
    }

    $fileName = $this->attachment->hashName();
    $filePath = $this->attachment->storeAs($directory, $fileName, 'r2');
    
    $jobParticipant->update([
        'attachment' => $filePath,
    ]);
    session()->flash('message', 'Bukti berhasil diperbarui!');
}




    public function closeAttachmentModal()
    {
        $this->viewAttachmentModal = false;
        $this->viewAttachmentPath = null;
    }

    public function getViewAttachmentPathProperty()
{
    if (!$this->selectedJobHistory) {
        return null;
    }

    $jobParticipant = JobParticipant::find($this->selectedJobHistory);

    if (!$jobParticipant || !$jobParticipant->attachment) {
        return null;
    }

    return Storage::disk('r2')->url($jobParticipant->attachment);
}


    public function render()
    {
        return view('livewire.pasukan.riwayat-pekerjaan')->layout('layouts.app');
    }
}
