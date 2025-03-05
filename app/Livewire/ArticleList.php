<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use App\Models\Notification;
use App\Models\NotificationRead;
use Illuminate\Support\Facades\Auth;

class ArticleList extends Component
{
    public $search = ''; // Properti untuk pencarian
    public $articleId;
    public $notification;

    public function mount()
    {
        // dd($this->notification);
    }

    public function render()
    {
        // Filter artikel berdasarkan judul
        $articles = Article::where('title', 'like', '%' . $this->search . '%')->where('status', 'published')->get();

        $notifications = Notification::where('notifiable_type', 'App\Models\Article')
                                        ->whereNotIn('id', function ($query) {
                                            $query->select('notification_id')
                                                ->from('notification_reads')
                                                ->where('user_id', Auth::id());
                                        })
                                        ->get();
        foreach ($notifications as $notification) {
            NotificationRead::create([
                'user_id' => auth()->user()->id,
                'notification_id' => $notification->id,
            ]);
        }
        $viewsCount = [];

        foreach ($articles as $article) {
            $notification = Notification::where('notifiable_id', $article->id)
                                        ->where('notifiable_type', 'App\Models\Article')
                                        ->pluck('id')
                                        ->first();

            // Hitung jumlah views
            $viewsCount[$article->id] = NotificationRead::where('notification_id', $notification)->count();
        }
        return view('livewire.article-list', compact('articles', 'viewsCount'))->layout('layouts.app');
    }
}
