<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Register extends Component
{
    public function render()
    {
        return view('livewire.auth.register-choice')
            ->layout('layouts.app');
    }
}
