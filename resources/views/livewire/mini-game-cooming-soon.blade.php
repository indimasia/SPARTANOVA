<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-orange-100 to-orange-300 overflow-hidden relative">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
        @for ($i = 0; $i < 20; $i++)
            <div class="absolute animate-float" style="
                left: {{ rand(0, 100) }}%;
                top: {{ rand(0, 100) }}%;
                animation-delay: {{ $i * 0.5 }}s;
                animation-duration: {{ rand(10, 20) }}s;
            ">
                <i class="fas fa-gamepad text-orange-400 opacity-20 text-{{ rand(3, 6) }}xl"></i>
            </div>
        @endfor
    </div>

    <!-- Animated gamer -->
    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 mb-8">
        <div class="gamer">
            <div class="head"></div>
            <div class="body"></div>
            <div class="arms">
                <div class="arm left"></div>
                <div class="arm right"></div>
            </div>
            <div class="legs">
                <div class="leg left"></div>
                <div class="leg right"></div>
            </div>
            <div class="controller"></div>
        </div>
    </div>

    <div class="text-center z-10">
        <h1 class="text-4xl md:text-6xl font-bold text-orange-800 mb-4 animate-pulse">
            Game Segera Hadir!
        </h1>
        <p class="text-xl md:text-2xl text-orange-700 mb-8 animate-bounce">
            Game akan segera hadir, Stay Tuned!
        </p>
        <div class="flex justify-center space-x-4 mb-8">
            <div class="flex items-center text-orange-800 animate-pulse">
                <i class="fas fa-clock mr-2"></i>
                <span>Tanggal Launch: TBA</span>
            </div>
            <div class="flex items-center text-orange-800 animate-pulse">
                <i class="fas fa-rocket mr-2"></i>
                <span>Siapkan Poinnya!</span>
            </div>
        </div>
        {{-- <div class="w-16 h-16 mx-auto mb-8 border-4 border-orange-600 border-t-orange-300 rounded-full animate-spin"></div> --}}
        
        {{-- @if (session()->has('message'))
            <div class="mb-4 text-green-600 font-semibold animate-fade-in-down">
                {{ session('message') }}
            </div>
        @endif --}}

        {{-- <form wire:submit.prevent="notifyMe" class="mb-4">
            <input type="email" wire:model="email" placeholder="Enter your email" 
                   class="px-4 py-2 border border-orange-400 rounded-l-md focus:outline-none focus:ring-2 focus:ring-orange-600 focus:border-transparent">
            <button type="submit" 
                    class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-r-md transition duration-300 animate-pulse">
                Notify Me
            </button>
        </form>
        @error('email') <span class="text-red-600 text-sm animate-shake">{{ $message }}</span> @enderror --}}
    </div>
    <style>
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
            100% { transform: translateY(0px) rotate(360deg); }
        }
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-float { animation: float 15s infinite; }
        .animate-fade-in-down { animation: fade-in-down 0.5s ease-out; }
        .animate-shake { animation: shake 0.5s ease-in-out; }
    
        /* Gamer animation styles */
        .gamer {
            width: 100px;
            height: 150px;
            position: relative;
        }
        .gamer .head {
            width: 40px;
            height: 40px;
            background-color: #FFA500;
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 30px;
        }
        .gamer .body {
            width: 60px;
            height: 70px;
            background-color: #FF8C00;
            position: absolute;
            top: 40px;
            left: 20px;
        }
        .gamer .arm {
            width: 20px;
            height: 50px;
            background-color: #FFA500;
            position: absolute;
            top: 50px;
        }
        .gamer .arm.left {
            left: 0;
            animation: move-left-arm 0.5s infinite alternate;
        }
        .gamer .arm.right {
            right: 0;
            animation: move-right-arm 0.5s infinite alternate;
        }
        .gamer .leg {
            width: 20px;
            height: 50px;
            background-color: #FF8C00;
            position: absolute;
            bottom: 0;
        }
        .gamer .leg.left { left: 20px; }
        .gamer .leg.right { right: 20px; }
        .gamer .controller {
            width: 60px;
            height: 30px;
            background-color: #333;
            position: absolute;
            bottom: 60px;
            left: 20px;
            border-radius: 5px;
        }
    
        @keyframes move-left-arm {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-15deg); }
        }
        @keyframes move-right-arm {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(15deg); }
        }
    </style>

</div>    

