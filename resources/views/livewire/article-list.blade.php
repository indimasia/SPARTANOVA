<div class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">

        <!-- Main content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            {{-- <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
              <div class="flex-1 px-4 flex justify-between">
                  <div class="flex-1 flex">
                      <form class="w-full flex md:ml-0" action="#" method="GET">
                          <label for="search-field" class="sr-only">Search</label>
                          <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                              <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                  <i class="ri-search-line h-5 w-5"></i>
                              </div>
                              <input id="search-field" class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm" placeholder="Search" type="search" name="search">
                          </div>
                      </form>
                  </div>
                  <div class="ml-4 flex items-center md:ml-6">
                      <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                          <span class="sr-only">View notifications</span>
                          <i class="ri-notification-line h-6 w-6"></i>
                      </button>
                      <div class="ml-3 relative">
                          <div>
                              <button class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu" aria-expanded="false" aria-haspopup="true">
                                  <span class="sr-only">Open user menu</span>
                                  <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/32" alt="">
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </div> --}}

            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <h1 class="text-2xl font-semibold text-gray-900">Articles</h1>
                    </div>
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        <div class="py-4">
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                                    <h2 class="text-lg leading-6 font-medium text-gray-900">Recent Articles</h2>
                                    <div>
                                        <input type="text" wire:model.live="search"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md"
                                            placeholder="Search articles by title">
                                    </div>

                                </div>
                                <div class="border-t border-gray-200">
                                    <ul role="list" class="divide-y divide-gray-200">
                                        <li>
                                            @if ($articles->isEmpty())
                                                <div class="flex flex-col items-center justify-center py-12">
                                                    <i class="fas fa-newspaper text-4xl text-gray-400 mb-4"></i>
                                                    <p class="text-lg text-gray-500 font-medium">Tidak ada artikel yang
                                                        ditemukan</p>
                                                    <p class="text-sm text-gray-400 mt-1">Coba cari dengan kata kunci
                                                        yang berbeda</p>
                                                </div>
                                            @else
                                                @foreach ($articles as $article)
                                                    <div class="px-4 py-4 sm:px-6">
                                                        <a href="{{ route('articles.detail', $article->slug) }}"
                                                            class="block">
                                                            <div class="flex items-center justify-between cursor-pointer"
                                                                wire:click="goToDetail({{ $article->slug }})">
                                                                <p class="text-sm font-medium truncate">
                                                                    {{ $article->title }}
                                                                </p>
                                                                <div class="ml-2 flex-shrink-0 flex">
                                                                    <p
                                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                        {{ $article->status }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <div class="mt-2 sm:flex sm:justify-between">
                                                            <div class="sm:flex">
                                                                <p class="flex items-center text-sm text-gray-500">
                                                                    <i
                                                                        class="fas fa-user flex items-center mr-1.5 h-5 w-5 text-yellow-700"></i>
                                                                    John Doe
                                                                </p>
                                                                <p
                                                                    class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                                    <i
                                                                        class="fas fa-calendar-alt flex items-center mr-1.5 h-5 w-5 text-yellow-700"></i>
                                                                    {{ $article->published_at }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                                <i
                                                                    class="fas fa-eye flex items-center mr-1.5 h-5 w-5 text-yellow-700"></i>
                                                                <p>
                                                                    1.2k views
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
