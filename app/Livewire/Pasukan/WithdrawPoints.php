<?php

namespace App\Livewire\Pasukan;

use Carbon\Carbon;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\ConversionRate;
use App\Models\UserPerformance;
use Illuminate\Support\Facades\Auth;

class WithdrawPoints extends Component
{
    public $editTransaction;
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
    public $minimumWithdraw;
    public $userTransactions = [];
    public $bank_account;

    public $isOpen = false;
    public $IsOpenEdit = false;

    public $rules = [
        'in_the_name_of' => ['required','string','min:3'],
        'no_bank_account' => ['required','integer','min:8'],
        'bank_account' => ['required','string','max:255'],
    ];

    protected $listeners = ['refreshTransactions', 'refreshData'];

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
            'user_id' => $transaction->user_id,
            'amount' => $transaction->amount,
            'status' => $transaction->status,
            'in_the_name_of' => $transaction->in_the_name_of,
            'no_bank_account' => $transaction->no_bank_account,
            'bank_account' => $transaction->bank_account,
            'description' => $transaction->description,
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

    public function updateWithdraw()
    {
        $this->validate([
            'editTransaction.in_the_name_of' => 'required|min:3',
            'editTransaction.bank_account' => 'required',
            'editTransaction.no_bank_account' => 'required|min:8',
        ]);


        $transaction = Transaction::findOrFail($this->editTransaction['id']);


        $transaction->update([
            'in_the_name_of' => $this->editTransaction['in_the_name_of'],
            'bank_account' => $this->editTransaction['bank_account'],
            'no_bank_account' => $this->editTransaction['no_bank_account'],
            'updated_at' => Carbon::now(), 
        ]);
        $this->IsOpenEdit = false;
        $this->refreshTransactions();
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

    public function editModal($transactionId) {
        $transaction = Transaction::findOrFail($transactionId);

        $this->editTransaction = [
            'id' => $transaction->id,
            'in_the_name_of' => $transaction->in_the_name_of,
            'bank_account' => $transaction->bank_account,
            'no_bank_account' => $transaction->no_bank_account,
        ];
    
        $this->IsOpenEdit = true;

    }

    public function closeModal() {
        $this->isOpen = false;
        $this->IsOpenEdit = false;
    }

    public function render()
    {
        Notification::whereNull('read_at')->update(['read_at' => now()]);
        $minimumWithdraw = Setting::where('key_name', 'Minimum Withdraw')->value('value');
        return view('livewire.pasukan.withdraw-points', [
            'minimumWithdraw' => $minimumWithdraw
        ])->layout('layouts.app');
    }
}