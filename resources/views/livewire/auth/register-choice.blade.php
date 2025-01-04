<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 -mt-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-4xl">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Pilih Tipe Akun Anda
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Silahkan pilih tipe akun yang sesuai dengan kebutuhan Anda
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-4xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Advertiser -->
                <div class="bg-white shadow-md rounded-lg p-6 text-center">
                    <div class="flex justify-center items-center mb-4">
                        <div class="bg-green-500 text-white w-12 h-12 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 7l-9-5 9 5 9-5-9 5zm0-7l9-5-9-5-9 5 9 5z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-xl font-semibold mb-2">Sebagai Pengiklan</h2>
                    <p class="text-gray-600 mb-4">Saya ingin mempromosikan dan mendapatkan komen positif untuk konten
                        medsos
                        saya.</p>
                    <a href="{{ route('pengiklan.register') }}"
                        class="inline-block bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Daftar
                        Sekarang</a>
                </div>

                <!-- Komentator -->
                <div class="bg-white shadow-md rounded-lg p-6 text-center">
                    <div class="flex justify-center items-center mb-4">
                        <div class="bg-yellow-500 text-white w-12 h-12 flex items-center justify-center rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 20h5v-2a2 2 0 00-2-2h-2.586a1 1 0 01-.707-.293l-6-6a1 1 0 010-1.414L11.586 7a1 1 0 01.707-.293H15a1 1 0 01.707.293l3.586 3.586a1 1 0 010 1.414l-6 6a1 1 0 01-.707.293H7a2 2 0 00-2 2v2h5" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="text-xl font-semibold mb-2">Sebagai Pejuang</h2>
                    <p class="text-gray-600 mb-4">Saya ingin mendapatkan penghasilan dengan menulis komentar berkualitas
                        di
                        medsos.</p>
                    <a href="{{ route('pejuang.register') }}"
                        class="inline-block bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">Daftar
                        Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>
