<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\URL;

class ImagePasukan extends Component
{
    public $image;

    public function mount($image)
    {
        $this->image = $image;
    }

    public function render()
    {
        return view('livewire.example-component', [
            'imageUrl' => $this->image ? URL::route('storage.fetch', ['filename' => $this->image]) : null,
        ]);
    }
}
