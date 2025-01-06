<?php

namespace App\Livewire\Pasukan;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.pasukan.dashboard')->layout('layouts.app');
    }
}
