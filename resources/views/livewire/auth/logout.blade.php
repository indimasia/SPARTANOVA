<div>
    <button onclick="confirmLogout()" wire:loading.attr="disabled"
        class="group flex items-center text-sm font-medium text-gray-600 hover:text-red-600 transition-all duration-200">
        <span class="relative inline-flex items-center gap-2">
            <i class="fas fa-sign-out-alt transform group-hover:-translate-x-0.5 transition-transform duration-200"></i>
            <span wire:loading.remove>Keluar</span>
            <span wire:loading>
                <i class="fas fa-spinner fa-spin"></i>
                Memproses...
            </span>
        </span>
    </button>

    <script>
        function confirmLogout() {
            showConfirmation(
                'Konfirmasi Keluar',
                'Apakah Anda yakin ingin keluar dari aplikasi?',
                () => {
                    @this.logout();
                }
            );
        }
    </script>
</div>
