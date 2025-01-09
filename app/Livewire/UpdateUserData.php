<?php

namespace App\Livewire;

use Livewire\Component;
use App\Enums\UserInterestEnum;

class UpdateUserData extends Component
{
    public $showModal = false;
    public $contact_wa;
    public $interest;
    public $interestValue=[];
    public $interestOptions = [];

    protected $rules = [
        'contact_wa' => 'required|string',
        'interestValue' => 'required|array|min:10',
    ];

    public function mount()
    {
        $this->interestOptions = UserInterestEnum::options();
        $user = auth()->user();
        $this->contact_wa = $user->contact_wa;
        $this->interest = $user->interest;

        if (empty($this->contact_wa) || empty($this->interest)) {
            $this->showModal = true;
        }
    }

    public function save()
    {
        $this->validate();

        $user = auth()->user();
        $user->update([
            'contact_wa' => $this->contact_wa,
            'interest' => $this->interestValue,
        ]);

        $this->showModal = false;

        session()->flash('message', 'Data berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.update-user-data');
    }
}
