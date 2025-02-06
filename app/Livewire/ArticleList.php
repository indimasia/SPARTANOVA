<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use App\Models\NotificationRead;

class ArticleList extends Component
{
    public $search = ''; // Properti untuk pencarian
    // public $articleId;

    public function mount()
    {
        // $this->articleId = $articleId;
        // // Tandai notifikasi sebagai dibaca
        // NotificationRead::create([
        //     'user_id' => auth()->user()->id,
        //     'notification_id' => $articleId,
        // ]);
    }

    public function render()
    {
        // Filter artikel berdasarkan judul
        $articles = Article::where('title', 'like', '%' . $this->search . '%')->where('status', 'published')->get();
        
        return view('livewire.article-list', compact('articles'))->layout('layouts.app');
    }
}
