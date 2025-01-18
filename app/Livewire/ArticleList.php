<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;

class ArticleList extends Component
{
    public $search = ''; // Properti untuk pencarian

    public function render()
    {
        // Filter artikel berdasarkan judul
        $articles = Article::where('title', 'like', '%' . $this->search . '%')->where('status', 'published')->get();
        
        return view('livewire.article-list', compact('articles'))->layout('layouts.app');
    }
}
