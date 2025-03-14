<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $user->is_active = false;
            $user->save();
        }

        Auth::logout();

        return redirect()->route('home');
    }
    public function render()
    {
        return view('livewire.auth.logout');
    }
}
