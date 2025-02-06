<?php

namespace App\Livewire\Pasukan;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\ConversionRate;
use App\Models\UserPerformance;
use Illuminate\Support\Facades\Auth;

class WithdrawPoints extends Component
{
    public $totalReward;
    public $selectedPoints;
    public $customPoints;
    public $conversionRate;
    public $withdrawOptions = [250, 300, 400, 500];
    public $statusFilter = 'all';
    public $transactions = [];
    public $notification = '';
    public $notificationType = '';

    protected $listeners = ['refreshTransactions'];

    public function mount()
    {
        $this->refreshUserData();
        $this->refreshTransactions();
        $this->conversionRate = ConversionRate::pluck('conversion_rate')->first() ?? 0;
    }

    public function refreshUserData()
    {
        $userPerformance = UserPerformance::where('user_id', Auth::id())->first();
        $this->totalReward = $userPerformance ? $userPerformance->total_reward : 0;
    }

    public function refreshTransactions()
{
    $query = Transaction::where('user_id', Auth::id())->orderBy('created_at', 'desc');

    if ($this->statusFilter !== 'all') {
        $query->where('status', $this->statusFilter);
    }

    $this->transactions = $query->get()->map(function ($transaction) {
        return [
            'id' => $transaction->id,
            'amount' => $transaction->amount,
            'status' => $transaction->status,
            'created_at' => $transaction->created_at->format('Y-m-d H:i:s'), // Format ke string yang bisa diproses
        ];
    })->toArray();
}


    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->refreshTransactions();
    }

    public function submitWithdraw($points)
    {
        $pointsToWithdraw = $points;

        if ($pointsToWithdraw < 250) {
            $this->notification = 'Penarikan minimum adalah 250 poin.';
            $this->notificationType = 'error';
            return;
        }

        if ($this->totalReward < $pointsToWithdraw) {
            $this->notification = 'Poin tidak mencukupi untuk penarikan.';
            $this->notificationType = 'error';
            return;
        }

        Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'withdrawal',
            'amount' => $pointsToWithdraw * $this->conversionRate,
            'status' => 'pending',
        ]);

        UserPerformance::where('user_id', Auth::id())
            ->decrement('total_reward', $pointsToWithdraw);

        $this->notification = 'Penarikan berhasil diajukan.';
        $this->notificationType = 'success';

        $this->refreshUserData();
        $this->refreshTransactions();
        $this->reset(['selectedPoints', 'customPoints']);
    }

    public function render()
    {
        return view('livewire.pasukan.withdraw-points')->layout('layouts.app');
    }
}