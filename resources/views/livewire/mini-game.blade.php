<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-orange-100 to-orange-300 overflow-hidden relative flex-col">
    <h1 class="text-4xl md:text-6xl font-bold text-orange-800 mb-6 animate-pulse">
        Gacha Box 🎁✨
    </h1>
    <p class="text-xl md:text-2xl text-orange-700 mb-8 animate-bounce">
        Klik salah satu box untuk membuka hadiahmu!
    </p>

    <!-- Grid Gacha Boxes -->
    <div class="grid grid-cols-3 md:grid-cols-4 gap-6">
        <script>
            let prizes = [
                { name: "🎉 Jackpot!", img: "https://via.placeholder.com/150/ffcc00/000000?text=Jackpot!" },
                { name: "💰 100 Coins", img: "https://via.placeholder.com/150/FFD700/000000?text=100+Coins" },
                { name: "🎮 New Skin", img: "https://via.placeholder.com/150/4CAF50/ffffff?text=New+Skin" },
                { name: "🎟️ Shopping Ticket", img: "https://via.placeholder.com/150/ff4500/ffffff?text=Shopping+Ticket" },
                { name: "⭐ Lucky Star", img: "https://via.placeholder.com/150/1E90FF/ffffff?text=Lucky+Star" },
                { name: "🔥 Power Boost", img: "https://via.placeholder.com/150/DC143C/ffffff?text=Power+Boost" }
            ];
        </script>
        @for ($i = 0; $i < 12; $i++)
            <div class="gacha-box group relative cursor-pointer w-28 h-28 md:w-32 md:h-32 flex items-center justify-center bg-orange-500 rounded-lg shadow-lg transition-transform transform hover:scale-105"
                 onclick="openBox(this)">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-orange-600 opacity-50 group-hover:opacity-70 rounded-lg"></div>
                <span class="text-white text-2xl font-bold relative z-10">🎁</span>
                <div class="absolute inset-0 flex items-center justify-center bg-white text-orange-800 text-lg font-semibold opacity-0 transition duration-500 rounded-lg hidden">
                    <span class="prize-text">?</span>
                </div>
            </div>
        @endfor
    </div>

    <!-- Modal Hadiah -->
    <div id="prize-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center transform scale-0 transition-transform duration-300">
            <h2 class="text-2xl font-bold text-orange-700 mb-4">🎁 Selamat! Kamu mendapatkan:</h2>
            <img id="prize-image" src="" alt="Hadiah" class="mx-auto w-40 h-40 mb-4 rounded-md shadow-lg">
            <p id="prize-text" class="text-lg font-semibold text-orange-800"></p>
            <button onclick="closePrizeModal()" class="mt-4 px-4 py-2 bg-orange-600 text-white font-bold rounded-md hover:bg-orange-700 transition">Tutup</button>
        </div>
    </div>

    <!-- Confetti Animation -->
    <div id="confetti" class="hidden absolute inset-0 flex items-center justify-center pointer-events-none">
        <canvas id="confettiCanvas"></canvas>
    </div>

    <script>
        function openBox(box) {
            if (box.classList.contains("opened")) return; // Cegah buka ulang

            box.classList.add("opened");
            let prizeElement = box.querySelector(".prize-text");
            let prizeContainer = box.querySelector(".absolute.inset-0");
            let randomPrize = prizes[Math.floor(Math.random() * prizes.length)];

            // Animasi flip
            box.style.transition = "transform 0.8s";
            box.style.transform = "rotateY(180deg)";

            setTimeout(() => {
                prizeElement.innerText = randomPrize.name;
                prizeContainer.classList.remove("hidden");
                prizeContainer.style.opacity = "1";

                // Tampilkan hadiah di modal
                showPrize(randomPrize.name, randomPrize.img);

                // Tampilkan confetti
                showConfetti();
            }, 800);
        }

        function showPrize(name, image) {
            let modal = document.getElementById("prize-modal");
            let modalBox = modal.querySelector("div");
            document.getElementById("prize-text").innerText = name;
            document.getElementById("prize-image").src = image;

            modal.classList.remove("hidden");
            setTimeout(() => {
                modalBox.classList.remove("scale-0");
                modalBox.classList.add("scale-100");
            }, 50);
        }

        function closePrizeModal() {
            let modal = document.getElementById("prize-modal");
            let modalBox = modal.querySelector("div");
            modalBox.classList.remove("scale-100");
            modalBox.classList.add("scale-0");
            setTimeout(() => modal.classList.add("hidden"), 300);
        }

        function showConfetti() {
            document.getElementById("confetti").classList.remove("hidden");
            let canvas = document.getElementById("confettiCanvas");
            let ctx = canvas.getContext("2d");
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            let confettiPieces = [];
            for (let i = 0; i < 100; i++) {
                confettiPieces.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    w: Math.random() * 10 + 5,
                    h: Math.random() * 20 + 10,
                    color: `hsl(${Math.random() * 360}, 100%, 50%)`,
                    velocity: Math.random() * 3 + 2
                });
            }

            function drawConfetti() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                confettiPieces.forEach(piece => {
                    ctx.fillStyle = piece.color;
                    ctx.fillRect(piece.x, piece.y, piece.w, piece.h);
                    piece.y += piece.velocity;
                    if (piece.y > canvas.height) piece.y = 0;
                });
                requestAnimationFrame(drawConfetti);
            }
            drawConfetti();

            setTimeout(() => {
                document.getElementById("confetti").classList.add("hidden");
            }, 3000);
        }
    </script>
</div>
