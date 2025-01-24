<div class="min-h-screen flex flex-col justify-center py-20 px-12 bg-gray-100 -mt-10">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-center text-4xl font-extrabold text-gray-900">
            Pilih Tipe Akun Anda
        </h2>
        <p class="mt-4 text-center text-lg text-gray-600">
            Silahkan pilih tipe akun yang sesuai dengan kebutuhan Anda
        </p>
    </div>

    <div class="mt-12 max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Advertiser -->
            <div class="bg-white rounded-lg p-8 text-center">
                <div class="flex justify-center items-center mb-6">
                    <div class="bg-green-500 text-white w-16 h-16 flex items-center justify-center rounded-full">
                        <i class="fas fa-bullhorn text-3xl"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-semibold mb-4">Sebagai Pengiklan</h2>
                <p class="text-gray-600 mb-6">Saya ingin beriklan, saya ingin memberdayakan pasukan iklan (adsman) Spartav untuk mempromosikan brand / produk saya.</p>
                <a href="{{ route('pengiklan.register') }}"
                    class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 text-lg font-medium">Daftar
                    Sekarang</a>
            </div>

            <!-- Komentator -->
            <div class="bg-white rounded-lg p-8 text-center">
                <div class="flex justify-center items-center mb-6">
                    <div class="bg-yellow-500 text-white w-16 h-16 flex items-center justify-center rounded-full">
                        <i class="fas fa-comment-alt text-3xl"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-semibold mb-4">Sebagai Pasukan</h2>
                <p class="text-gray-600 mb-6">Saya ingin mendapatkan penghasilan tambahan dengan melakukan kegiatan promosi pemasaran, penjualan, dan iklan di akun medsos saya.</p>
                <a href="{{ route('pasukan.register') }}"
                    class="inline-block bg-yellow-500 text-white px-6 py-3 rounded-lg hover:bg-yellow-600 text-lg font-medium">Daftar
                    Sekarang</a>
            </div>
        </div>
    </div>
</div>
