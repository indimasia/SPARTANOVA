<div>
    <div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center p-4 overflow-hidden">
        <div id="particles-js" class="absolute inset-0 z-0"></div>

        <div class="relative z-10 max-w-4xl w-full space-y-8">
            <h1 class="text-5xl md:text-7xl font-extrabold text-white text-center mb-4 drop-shadow-lg">
                Gacha Game 🎁✨
            </h1>
            <p class="text-xl md:text-2xl text-white text-center mb-8 drop-shadow">
                Pilih kotak untuk mengambil hadiahmu!
            </p>

            

            @if (session()->has('error'))
                <div class="text-red-500 font-bold text-center">
                    {{ session('error') }}
                </div>
            @endif
            <div class="flex justify-between">
                <p class="text-lg text-white text-center font-semibold">
                    Setiap putaran membutuhkan <span class="text-yellow-300 font-bold">{{ $poingame }} Poin 🪙</span>
                </p>
                <p class="text-lg text-white text-center font-semibold">
                    Poin Anda: <span class="text-yellow-300 font-bold">{{ $userPerformance }} 🪙</span>
                </p>
            </div>
            <p class="text-lg text-white text-center font-semibold">
                <div class="grid grid-cols-3 md:grid-cols-4 gap-4 md:gap-6 max-w-4xl mx-auto">
                    @for ($i = 0; $i < 12; $i++)
                        <button wire:click="spin" class="relative w-full h-24 md:h-32 cursor-pointer overflow-hidden rounded-2xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:rotate-3">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-400 to-pink-500"></div>
                            <div class="absolute inset-0 flex items-center justify-center backdrop-blur-sm bg-white bg-opacity-30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 md:h-16 md:w-16 text-white drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                            </div>
                        </button>
                    @endfor
                </div>
                
        </div>

        <!-- Prize Modal -->
        @if ($prize)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 reward-modal">
                <div class="bg-white bg-opacity-80 backdrop-blur-lg p-8 rounded-2xl shadow-2xl max-w-sm w-full m-4">
                    <h2 class="text-3xl font-bold text-center mb-4 text-purple-700">
                        {{ $prize['name'] === 'ZONK' ? 'Better luck next time!' : 'Congratulations! 🎉' }}
                    </h2>
                    <div class="flex flex-col items-center">
                        <img src="{{ $prize['image'] ? asset('storage/' . $prize['image']) : 'https://placehold.co/400x400?text=Tidak+Ada+Gambar' }}" alt="Prize" class="w-48 h-48 object-cover mb-4 rounded-lg shadow-lg">
                        <p class="text-xl font-semibold text-center text-purple-600 mb-2">
                            {{ $prize['name'] === 'ZONK' ? 'You got:' : 'You won:' }}
                        </p>
                        <p class="text-2xl font-bold text-purple-700 mt-2">
                            {{ $prize['name'] }}
                        </p>
                    </div>
                    <button wire:click="$set('prize', null)" class="mt-6 w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                        Close
                    </button>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tsparticles/confetti@3.0.3/tsparticles.confetti.bundle.min.js"></script>
    {{-- <script>
        // Initialize particles.js
        particlesJS("particles-js", {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: "#ffffff" },
                shape: { type: "circle" },
                opacity: { value: 0.5, random: false },
                size: { value: 3, random: true },
                line_linked: { enable: true, distance: 150, color: "#ffffff", opacity: 0.4, width: 1 },
                move: { enable: true, speed: 2, direction: "none", random: false, straight: false, out_mode: "out", bounce: false }
            },
            interactivity: {
                detect_on: "canvas",
                events: { onhover: { enable: true, mode: "repulse" }, onclick: { enable: true, mode: "push" }, resize: true },
                modes: { repulse: { distance: 100, duration: 0.4 }, push: { particles_nb: 4 } }
            },
            retina_detect: true
        });
    </script> --}}
    <script>
        function startConfetti() {
            const count = 200,
            defaults = {
                origin: { y: 0.7 },
            };

            function fire(particleRatio, opts) {
            confetti(
                Object.assign({}, defaults, opts, {
                particleCount: Math.floor(count * particleRatio),
                })
            );
            }

            fire(0.25, {
            spread: 26,
            startVelocity: 55,
            });

            fire(0.2, {
            spread: 60,
            });

            fire(0.35, {
            spread: 100,
            decay: 0.91,
            scalar: 0.8,
            });

            fire(0.1, {
            spread: 120,
            startVelocity: 25,
            decay: 0.92,
            scalar: 1.2,
            });

            fire(0.1, {
            spread: 120,
            startVelocity: 45,
            });
        }

        // Listen for changes to the reward-modal
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length) {
                    mutation.addedNodes.forEach((node) => {
                        if (node.classList && node.classList.contains('reward-modal')) {
                            // Only start confetti if it's not a ZONK prize
                            if (!document.querySelector('.reward-modal').textContent.includes('Better luck next time!')) {
                                startConfetti();
                            }
                        }
                    });
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    </script>
</div>
