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
    public $in_the_name_of = '';
    public $no_bank_account = '';

    public $bank_account;

    public $isOpen = false;

    public $rules = [
        'in_the_name_of' => ['required','string','min:3'],
        'no_bank_account' => ['required','integer','min:8'],
        'bank_account' => ['required','string','max:255'],
    ];

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
            'in_the_name_of' => $transaction->in_the_name_of,
            'no_bank_account' => $transaction->no_bank_account,
            'bank_account' => $transaction->bank_account,
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

        $validated = $this->validate([
            'in_the_name_of' => ['required', 'min:3'],
            'no_bank_account' => ['required', 'min:8'],
            'bank_account' => 'required',
        ],[
            'in_the_name_of.required' => 'Anda harus mengisi atas nama',
            'in_the_name_of.min' => 'Anda harus mengisi atas nama minimal 3 karakter',
            'no_bank_account.required' => 'Anda harus mengisi nomor rekening',
            'no_bank_account.min' => 'Anda harus mengisi nomor rekening minimal 8 karakter',
            'bank_account.required' => 'Anda harus mengisi metode tranfer',
        ]);


         Transaction::create([
            'user_id' => Auth::id(),
            'type' => 'withdrawal',
            'amount' => $pointsToWithdraw * $this->conversionRate,
            'status' => 'pending',
            'in_the_name_of' => $validated['in_the_name_of'],
            'no_bank_account' => $validated['no_bank_account'],
            'bank_account' => $validated['bank_account']
        ]);

        UserPerformance::where('user_id', Auth::id())
            ->decrement('total_reward', $pointsToWithdraw);

        $this->notification = 'Penarikan berhasil diajukan.';
        $this->notificationType = 'success';

        $this->refreshUserData();
        $this->refreshTransactions();
        $this->reset(['selectedPoints', 'customPoints']);
        $this->isOpen = false;
    }

    public function openModal($points) {
        
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

        $this->isOpen = true;

        


    }

    public function closeModal() {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.pasukan.withdraw-points')->layout('layouts.app');
    }
}