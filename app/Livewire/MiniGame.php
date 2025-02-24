<?php

namespace App\Livewire;

use App\Models\Reward;
use Livewire\Component;

class MiniGame extends Component
{
    
    public $prize;
    
    public function spin()
    {
        $user = auth()->user(); // Ambil user yang login

        // Cek apakah user memiliki catatan di UserPerformance
        $performance = $user->userPerformance; // Pastikan ada relasi di model User

        if (!$performance || $performance->total_reward < 10) {
            session()->flash('error', 'You do not have enough points to spin.');
            return;
        }

        // Kurangi 10 poin sebelum melakukan spin
        $performance->decrement('total_reward', 10);

        $rewards = Reward::where('status', 'available')->where('quantity', '>', 0)->get();

        // Tambahkan ZONK sebagai pilihan
        $rewardPool = [];
        foreach ($rewards as $reward) {
            for ($i = 0; $i < $reward->weight; $i++) {
                $rewardPool[] = $reward;
            }
        }

        // Ambil hasil spin
        $selectedReward = $rewardPool[array_rand($rewardPool)];

        if ($selectedReward) {
            if ($selectedReward->name != 'ZONK') {
                $selectedReward->decrement('quantity');
            }
            $this->prize = [
                'name' => $selectedReward->name,
                'image' => $selectedReward->image,
            ];
            $this->dispatch('triggerConfetti');
        }
    }


    public function render()
    {
        return view('livewire.mini-game')->layout('layouts.app');
    }
}

