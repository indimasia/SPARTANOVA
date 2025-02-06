<?php

namespace App\Livewire\Pasukan;

use Livewire\Component;
use App\Enums\JobStatusEnum;
use App\Models\Notification;
use Livewire\WithFileUploads;
use App\Models\JobParticipant;
use Illuminate\Support\Facades\Auth;

class RiwayatPekerjaan extends Component
{
    use WithFileUploads;
    public $attachment;
    public $notification;
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
        // dd($this->attachment);
        $this->validate([
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'attachment.required' => 'File bukti harus diunggah.',
            'attachment.file' => 'Harap unggah file yang valid.',
            'attachment.mimes' => 'File harus berupa: jpg, jpeg, png, pdf.',
            'attachment.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);


        $jobParticipant = JobParticipant::find($this->selectedJobHistory);

        $fileName = $this->attachment->store('attachments', 'public');
        $path = basename($fileName);
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
            $this->viewAttachmentPath = asset('attachments/' . $jobParticipant->attachment);
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

    // Hapus file lama jika ada
    if ($jobParticipant->attachment) {
        \Storage::disk('attachments')->delete($jobParticipant->attachment);
    }

    // Simpan file baru
    $fileName = $this->attachment->store('attachments', 'public');
    $path = basename($fileName);
    $jobParticipant->update([
        'attachment' => $path,
    ]);

    $this->viewAttachmentPath = asset('attachments/' . $path);
    session()->flash('message', 'Bukti berhasil diperbarui!');
}


    public function closeAttachmentModal()
    {
        $this->viewAttachmentModal = false;
        $this->viewAttachmentPath = null;
    }

    public function render()
    {
        Notification::whereNull('read_at')->update(['read_at' => now()]);
        
        return view('livewire.pasukan.riwayat-pekerjaan')->layout('layouts.app');
    }
}
