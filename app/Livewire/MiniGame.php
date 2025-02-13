<?php

namespace App\Livewire;

use Livewire\Component;

class MiniGame extends Component
{
    public $email = '';

    public function notifyMe()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        // Here you would typically save the email to your database
        // or send it to your mailing list provider
        session()->flash('message', 'Thank you! We\'ll notify you when the Mini Game is ready.');
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.mini-game')->layout('layouts.app');
    }
}

