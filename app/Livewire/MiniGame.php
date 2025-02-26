<?php

namespace App\Livewire;

use App\Models\Reward;
use App\Models\Setting;
use Livewire\Component;
use App\Models\UserPerformance;

class MiniGame extends Component
{
    
    public $prize;
    
    public function spin()
    {
        $user = auth()->user();
        $performance = $user->userPerformance;
        $poingame = Setting::where('key_name', 'Poin Game')->pluck('value')->first();

        if (!$performance || $performance->total_reward < $poingame) {
            session()->flash('error', 'You do not have enough points to spin.');
            return;
        }

        $performance->decrement('total_reward', $poingame);

        $rewards = Reward::where('is_available', 1)->where('quantity', '>', 0)->get();

        $rand = mt_rand() / mt_getrandmax();
        
        $cumulative = 0;
        $selectedReward = null;
    
        foreach ($rewards as $section) {
            $cumulative += $section['probability'];

            if ($rand <= $cumulative) {
                $selectedReward = $section;
                break;
            }
        }
        if (!$selectedReward) {
            $selectedReward = Reward::where('name', 'ZONK')->first();
            $this->prize = [
                'name' => $selectedReward->name,
                'image' => $selectedReward->image,
            ];
        } else {
            $selectedReward->decrement('quantity');
    
            if ($selectedReward->quantity <= 0) {
                $selectedReward->update(['is_available' => 0]);
            }
        }

        $this->prize = [
            'name' => $selectedReward->name,
            'image' => $selectedReward->image,
        ];
    }



    public function render()
    {
        $userPerformance = UserPerformance::where('user_id', auth()->user()->id)->pluck('total_reward')->first();
        $poingame = Setting::where('key_name', 'Poin Game')->pluck('value')->first();
        return view('livewire.mini-game', compact('userPerformance', 'poingame'))->layout('layouts.app');
    }
}

