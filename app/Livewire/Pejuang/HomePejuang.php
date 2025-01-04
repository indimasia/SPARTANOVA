<?php

namespace App\Livewire\Pejuang;

use Livewire\Component;

class HomePejuang extends Component
{
    public function render()
    {
        return view('livewire.pejuang.home-pejuang')->layout('layouts.app');
    }
}
