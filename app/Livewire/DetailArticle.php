<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;

class DetailArticle extends Component
{
    public $article;

    public function mount($slug)
    {
        $this->article = Article::where('slug', $slug)->first();
    }

    public function render()
    {
        return view('livewire.detail-article', ['article' => $this->article])->layout('layouts.app');
    }
}
