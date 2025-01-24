<div>
    <div class="bg-black text-white min-h-screen">
        <!-- Header Section -->
        <div class="flex justify-center bg-white py-4 md:py-6">
            <img
                src="{{ asset('images/spartav_logo.png') }}"
                alt="SPARTAV Logo"
                class="h-24 md:h-48 w-auto"
            />
        </div>
        <div class="bg-[#FFD700] py-4 md:py-8 text-black">
            <div class="container bg-[#FFD700]">
                <div class="text-black text-center py-4 md:py-6">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4 md:mb-10">Selamat Datang di Era Ekonomi Digital</h1>
                    <p class="max-w-4xl mx-auto mt-2 md:mt-4 text-lg md:text-2xl px-4 md:px-0">
                        SPARTAV adalah platform iklan digital inovatif yang memberdayakan masyarakat sebagai mitra aktif dalam
                        menyebarkan kampanye brand Anda. Melalui basis jaringan komunitas yang luas, kami memastikan pesan Anda
                        sampai ke audiens yang tepat secara cepat, efektif, dan lebih terjangkau.
                    </p>
                </div>
            </div>
        </div>
        <div class="py-3 md:py-6 bg-white">
        </div>
    
        <!-- Micro Marketing Section -->
        <div class="py-4 md:py-8">
            <div class="container bg-black mx-auto text-[#FFD700] px-4 text-center">
                <h2 class="text-3xl md:text-5xl font-bold mb-4 md:mb-10">Micro Marketing & Micro Targeting</h2>
                <p class="max-w-4xl mx-auto mt-2 md:mt-4 mb-8 md:mb-16 text-gray-400 text-lg md:text-2xl">
                    Dengan fitur segmentasi pasukan iklan berbasis gender, generasi usia, lokasi, dan minat memungkinkan pasukan iklan
                    dan pemasaran menyampaikan pesan Anda ke circle yang relevan dengan target market Anda.
                </p>
            </div>
            @if (!auth()->check())
            <div class="bg-white flex flex-col md:flex-row justify-center items-center gap-4 p-4">
                <a href="{{ route('register') }}">
                  <button class="min-w-[200px] bg-[#FFD700] text-black hover:bg-[#FFD700]/90 rounded-xl text-base md:text-2xl font-bold px-4 py-2">
                    Daftar Sekarang
                  </button>
                </a>
                <a href="{{ route('login') }}">
                  <button class="min-w-[200px] border border-black text-red-500 hover:bg-red-500 hover:text-white rounded-xl text-base md:text-2xl font-bold px-4 py-2">
                    Masuk
                  </button>
                </a>
            </div>
            @endif
              
              
        </div>

        <div class="py-5 md:py-10 bg-black">
        </div>
    
        <!-- Why Spartav Section -->
        <div class="bg-white py-8 md:py-16 text-black flex items-center justify-center px-4 md:px-0 -mt-6 md:-mt-10">
            <div class="container max-w-4xl bg-[#FFD700] py-6 md:py-10 rounded-3xl border-4 border-black relative -mt-12 md:-mt-24">
                <h2 class="text-2xl md:text-3xl font-bold mb-4 text-center">Kenapa harus iklan di Spartav ?</h2>
                <ul class="space-y-2 max-w-2xl mx-auto text-lg md:text-2xl px-4 md:px-0">
                    <li class="flex items-center"><span class="mr-2">•</span>Jangkauan lebih luas dan terukur</li>
                    <li class="flex items-center"><span class="mr-2">•</span>Segmentasi Audiens yang tepat sasaran</li>
                    <li class="flex items-center"><span class="mr-2">•</span>Dikampanyekan oleh real human, bukan bot</li>
                    <li class="flex items-center"><span class="mr-2">•</span>Pasukan iklan bisa ditentukan berdasarkan lokasi dan gen usia</li>
                    <li class="flex items-center"><span class="mr-2">•</span>Menggunakan teknik micro targeting dan micro marketing</li>
                    <li class="flex items-center"><span class="mr-2">•</span>Turut membuka lapangan kerja lebih luas</li>
                </ul>
                @if (!auth()->check())
                <div class="text-center mt-6 absolute inset-x-0 bottom-0 transform translate-y-1/2">
                    <a href="{{ route('register') }}">
                        <button class="bg-white text-black rounded-2xl border-4 border-black text-lg md:text-2xl font-bold px-4 md:px-6 py-2 md:py-3">
                            Daftar Sekarang
                        </button>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="bg-[#FFD700] py-6 md:py-12 text-black">
            <div class="container mx-auto px-4 text-center">
                <p class="text-4xl md:text-6xl font-bold text-red-700 mb-4">Siap menjadi Pasukan Iklan Digital?</p>
                <p class="text-2xl md:text-4xl font-bold mb-4">
                    Ayo gabung dan menjadi bagian dari<br>
                    Era Baru Pemasaran Digital
                </p>
            </div>
        </div>  
        <div class="bg-black py-6 md:py-12 text-white">
            <div class="container mx-auto px-4 text-center">
                <p class="max-w-3xl text-lg md:text-2xl mx-auto mb-8">
                    Sebagai Pasukan SPARTAV, tugas Anda menjadi pasukan iklan dengan cara menyelesaikan misi pekerjaan online
                    seperti membagikan konten, memberikan komentar, atau mengisi survei. Upload bukti penyelesaian, dan dapatkan
                    bayaran atas kontribusi Anda dalam mendukung kampanye mitra SPARTAV.
                </p>
            </div>
        </div>  
    
        <!-- Rebahan Section -->
        <div class="bg-white text-black py-4 md:py-8">
            <div class="container mx-auto px-4">
                <div class="text-center mb-4 md:mb-8">
                    <h2 class="text-4xl md:text-7xl font-bold mb-2">Rebahan Dapat Cuan,</h2>
                    <h3 class="text-3xl md:text-5xl text-red-700 font-bold mb-4">Jadilah Pasukan Iklan dan Pemasaran</h3>
                    <p class="text-xl md:text-3xl mb-4">
                        yang penting bisa dan mau posting jualan,<br>
                        bisa mengerjakan misi sesuai arahan, pasti dapat cuan
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-black text-white py-4 md:py-8">
            <div class="container mx-auto px-4">
                @if (!auth()->check())
                <div class="text-center mt-6 flex flex-col md:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}">
                    <button class="w-[280px] bg-[#FFD700] text-black hover:bg-[#FFD700]/90 rounded-2xl text-xl md:text-3xl font-bold px-4 md:px-6 py-2 md:py-4">
                        Daftar Sekarang
                    </button>
                    </a>
                    <a href="{{ route('login') }}">
                    <button class="w-[280px] border border-white rounded-2xl bg-white text-red-700 text-xl md:text-3xl font-bold px-4 md:px-6 py-2 md:py-4">
                        Masuk
                    </button>
                    </a>
                </div>
                @endif
            </div>
        </div>
                    
        <div class="flex justify-center px-4 md:px-0">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white p-4 py-8 md:py-12 rounded-xl max-w-lg h-auto text-black relative mb-16">
                    <div class="flex flex-col md:flex-row items-center gap-4">
                      <img src="{{ asset('images/pasukan.png') }}" alt="Pengiklan" class="w-1/2 md:w-1/3 h-auto">
                      <div>
                        <h4 class="text-2xl md:text-3xl font-bold mb-3">Pasukan</h4>
                        <h4 class="text-xl md:text-2xl mb-3"><i>Ada internet? Yups, kamu bisa berpenghasilan</i></h4>
                      </div>
                    </div>
                    <ul class="space-y-2 text-lg md:text-xl pl-6 mt-4">
                      <li class="list-disc">Bisa dikerjakan dari mana saja</li>
                      <li class="list-disc">Misi pekerjaan mudah!</li>
                      <li class="list-disc">Pilih Misi sesuai minat dan keahlian</li>
                      <li class="list-disc">Dapat reward setiap selesai misi</li>
                      <li class="list-disc">Punya skill jualan, lebih menguntungkan</li>
                      <li>Ada banyak hadiah menarik</li>
                    </ul>
                    @if (!auth()->check())
                    <a href="{{ route('register') }}">
                        <button class="absolute left-1/2 bottom-0 border-4 border-black transform -translate-x-1/2 translate-y-1/2 bg-yellow-500 text-black px-4 md:px-6 py-2 rounded-2xl text-lg md:text-xl font-bold shadow-lg">
                        Daftar Sekarang
                        </button>
                    </a>
                    @endif
                  </div>
                  
                  <div class="bg-[#FFD700] p-4 py-8 md:py-12 rounded-xl max-w-lg text-black relative mb-16">
                    <div class="flex flex-col md:flex-row items-center gap-4">
                      <img src="{{ asset('images/pengiklan.png') }}" alt="Pengiklan" class="w-1/2 md:w-1/3 h-auto">
                      <div class="flex flex-col justify-center">
                        <h4 class="text-2xl md:text-3xl font-bold mb-3">Pengiklan</h4>
                        <h4 class="text-xl md:text-2xl mb-3"><i>Pasukan iklan siap mempromosikan brandmu!</i></h4>
                      </div>
                    </div>
                    <ul class="space-y-2 text-lg md:text-xl pl-6 mt-4">
                      <li class="list-disc">Target market lebih tepat sasaran</li>
                      <li class="list-disc">Pilih pasukan berdasarkan lokasi & demografis sesuai kebutuhan</li>
                      <li class="list-disc">Biaya marketing lebih efisien</li>
                      <li class="list-disc">Pantau hasil promosi secara real-time</li>
                      <li class="list-disc">Terpantau dan terukur</li>
                    </ul>
                    @if (!auth()->check())
                    <a href="{{ route('register') }}">
                        <button class="absolute left-1/2 bottom-0 border-4 border-black transform -translate-x-1/2 translate-y-1/2 bg-white text-black px-4 md:px-6 py-2 rounded-2xl text-lg md:text-xl font-bold shadow-lg">
                        Daftar Sekarang
                        </button>
                    </a>
                    @endif
                  </div>
            </div>
        </div>
    
        <!-- Steps Section -->
        <div class="bg-white py-6 md:py-12">
            <div class="container mx-auto px-4">
                <p class="text-black text-lg md:text-xl text-center px-4 md:px-28 mb-6 md:mb-10">
                    SPARTAV adalah platform manajemen periklanan dan pemasaran digital yang memfasilitasi advertiser untuk memperluas target market dengan memberdayakan pasukan iklan (adsman) untuk melakukan kegiatan branding, marketing, dan selling secara online.
                </p>
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg md:text-xl font-bold mx-auto mb-6 text-center text-black bg-gray-200 rounded-xl max-w-lg px-4 py-3 border-4 border-black">Sebagai Pasukan Iklan</h3>
                        <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-64 text-center ">
                                    <h4 class="font-bold text-center text-black text-xl md:text-2xl bg-yellow-300 rounded-2xl px-4 py-3 border-4 border-black">Daftar</h4>
                                    <p class="text-black text-lg md:text-xl mt-2">Buat Akun, lengkapi <br>profilmu</p>
                                </div>
                                <div class="w-64 text-center">
                                    <h4 class="font-bold text-center text-black text-xl md:text-2xl bg-yellow-300 rounded-2xl px-4 py-3 border-4 border-black">Pilih Misi</h4>
                                    <p class="text-black text-lg md:text-xl mt-2">Temukan misi pekerjaan sesuai minat & keahlian</p>
                                </div>
                                <div class="w-64 text-center">
                                    <h4 class="font-bold text-center text-black text-xl md:text-2xl bg-yellow-300 rounded-2xl px-4 py-3 border-4 border-black">Kerjakan Misi</h4>
                                    <p class="text-black text-lg md:text-xl mt-2">Kerjakan misi sesuai instruksi, dan laporkan</p>
                                </div>
                                <div class="w-64 text-center">
                                    <h4 class="font-bold text-center text-black text-xl md:text-2xl bg-yellow-300 rounded-2xl px-4 py-3 border-4 border-black">Dapat Reward</h4>
                                    <p class="text-black text-lg md:text-xl mt-2">Kerjakan misi sesuai instruksi, dan laporkan</p>
                                </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg md:text-xl font-bold mx-auto mb-6 text-center text-black bg-gray-200 rounded-xl max-w-lg px-4 py-3 border-4 border-black">Sebagai Pengiklan</h3>
                        <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-64 text-center">
                                    <h4 class="font-bold text-center text-white text-xl md:text-2xl bg-black rounded-2xl px-4 py-3 border-4 border-black">Daftar</h4>
                                    <p class="text-black text-lg md:text-xl mt-2">Buat Akun dalam Hitungan Menit</p>
                                </div>
                                <div class="w-64 text-center">
                                    <h4 class="font-bold text-center text-white text-xl md:text-2xl bg-black rounded-2xl px-4 py-3 border-4 border-black">Buat Misi</h4>
                                    <p class="text-black text-lg md:text-xl mt-2">Tentukan budget, dan area targetmu</p>
                                </div>
                                <div class="w-64 text-center">
                                    <h4 class="font-bold text-center text-white text-xl md:text-2xl bg-black rounded-2xl px-4 py-3 border-4 border-black">Monitoring</h4>
                                    <p class="text-black text-lg md:text-xl mt-2">Pantau perkembangan secara realtime</p>
                                </div>
                                <div class="w-64 text-center">
                                    <h4 class="font-bold text-center text-white text-xl md:text-2xl bg-black rounded-2xl px-4 py-3 border-4 border-black">Dapat Laporan</h4>
                                    <p class="text-black text-lg md:text-xl mt-2">Dapatkan insight dari hasil kinerja pasukan iklan</p>
                                </div>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="flex justify-center mt-8 md:mt-14">
                    <button class="bg-black text-white rounded-2xl text-lg md:text-xl font-bold px-8 md:px-16 py-3 md:py-4 min-w-48">
                        Our Clients and Partners
                    </button>
                </div>
                <div class="flex flex-wrap justify-center items-center gap-4 mt-6 md:mt-10">
                    <img src="{{ asset('images/bri.jpg') }}" alt="client and partner 1" class="w-24 md:w-48 h-auto">
                    <img src="{{ asset('images/dna.png') }}" alt="client and partner 2" class="w-24 md:w-48 h-auto">
                    <img src="{{ asset('images/ime.jpg') }}" alt="client and partner 3" class="w-24 md:w-48 h-auto">
                    <img src="{{ asset('images/kenjaya.jpg') }}" alt="client and partner 4" class="w-24 md:w-48 h-auto">
                    <img src="{{ asset('images/skin.jpg') }}" alt="client and partner 5" class="w-24 md:w-48 h-auto">
                </div>                
            </div>
        </div>
    </div>
</div>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    spartav: {
                        yellow: '#FFD700',
                        black: '#000000',
                    }
                }
            }
        }
    }
</script>