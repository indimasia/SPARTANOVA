<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class Topup extends Component
{
    use WithFileUploads;

    public $selectedAmount = null;
    public $transferProof;
    public $topupAmounts = [
        50000 => '50,000',
        100000 => '100,000',
        250000 => '250,000',
        500000 => '500,000',
        1000000 => '1,000,000',
    ];
    public $bankAccount = [
        'bank' => 'Bank XYZ',
        'number' => '1234567890',
        'name' => 'PT Example Company',
    ];

    public function selectAmount($amount)
    {
        $this->selectedAmount = $amount;
    }

    public function uploadProof()
    {
        $this->validate([
            'transferProof' => 'image|max:1024', // 1MB Max
        ]);

        // Here you would typically save the proof and process the topup
        // For this example, we'll just show a success message
        session()->flash('message', 'Bukti transfer berhasil diunggah. Topup sedang diproses.');

        $this->reset(['selectedAmount', 'transferProof']);
    }

    public function render()
    {
        return view('livewire.topup')->layout('layouts.app');
    }
}
