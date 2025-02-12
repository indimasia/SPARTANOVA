<div class="bg-gray-50">

    <div class="mx-auto px-10 py-10">
      <article class="bg-white shadow-lg rounded-lg overflow-hidden">
        @if($article->image)
          <div class="relative h-64 sm:h-80 md:h-96">
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
          </div>
        @else
          <div class="relative h-64 sm:h-80 md:h-96">
            <img src="https://placehold.co/400x400?text=Tidak+Ada+Gambar" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
          </div>
        @endif
  
        <div class="p-6 sm:p-8">
          <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">{{ $article->title }}</h1>
          <div class="flex items-center text-sm text-gray-600 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 002-2V7H3v10a2 2 0 002 2z" />
            </svg>
            <time datetime="2024-01-01">{{ $article->published_at }}</time>
            <span class="mx-2">•</span>
            <span class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z" />
              </svg>
              {{ $article->status }}
            </span>
          </div>
  
          <div class="prose prose-lg max-w-none">
            <p>{!! $article->content !!}</p>
          </div>
        </div>
  
        <div class="bg-gray-100 px-6 py-4 sm:px-8 sm:py-6">
          {{-- <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wider mb-2">Article Info</h2>
          <code class="text-sm bg-gray-200 text-gray-800 px-2 py-1 rounded">This is a static info text</code> --}}
        </div>
      </article>
    </div>
  
  </div>