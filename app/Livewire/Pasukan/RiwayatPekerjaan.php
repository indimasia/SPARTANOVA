<?php

namespace App\Livewire\Pasukan;

use Livewire\Component;
use App\Enums\JobStatusEnum;
use App\Models\Notification;
use App\Services\OcrService;
use Livewire\WithFileUploads;
use App\Models\JobParticipant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RiwayatPekerjaan extends Component
{
    use WithFileUploads;
    public $attachment;
    public $notification;
    public $jobParticipant;
    public $jobHistory;
    public $selectedJobHistory;
    public $showModal = false;
    public $dropdownVisible = false;
    protected $listeners = ['closeDropdown' => 'closeDropdown'];
    public $viewAttachmentModal = false;
    public $viewAttachmentPath;
    public $status;
    public $showUpdateView = false;
    public $selectedJobId;
    public $views;
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
        'attachment' => 'required|file|mimes:jpg,jpeg,png|max:2048',
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
    $this->attachment->storeAs("pasukan/attachment/{$userId}", $fileName, 'r2');




    $this->attachment->storeAs('ocr', $fileName, 'public');
    
    // ✅ Pastikan path benar untuk OCR
    $localPath = public_path("ocr/{$fileName}");
    
    if (!file_exists($localPath)) {
        dd('test');
        session()->flash('error', 'File gagal disimpan.');
        return;
    }
    dd($localPath);

    // ✅ Jalankan OCR dengan path lokal
    $ocrViews = OcrService::extractText($localPath);
    dd($ocrViews); // Debug hasil OCR
    
    if ($ocrViews === null) {
        session()->flash('error', 'Gagal membaca angka dari screenshot');
        return;
    }

    // Update database
    $jobParticipant->update([
        'attachment' => $path,
        'ocr_extracted_views' => $ocrViews,
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
            $this->jobParticipant = $jobParticipant;
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

    public function showUpdateViewModal($jobId)
    {
        $jobParticipant = JobParticipant::find($jobId);
        $this->selectedJobId = $jobId;
        $this->views = $jobParticipant->views;
        $this->showUpdateView = true;
    }

    public function updateView()
    {
        $this->validate([
            'views' => 'required|integer|min:0',
        ]);

        $jobParticipant = JobParticipant::find($this->selectedJobId);

        $jobParticipant->views = $this->views;
        $jobParticipant->save();

        $this->showUpdateView = false;
        session()->flash('message', 'Jumlah view berhasil diperbarui!');
    }


    public function render()
    {
        Notification::whereNull('read_at')->update(['read_at' => now()]);
        
        return view('livewire.pasukan.riwayat-pekerjaan')->layout('layouts.app');
    }
}