<div class="bg-white shadow-sm rounded-lg w-full">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-2 sm:space-y-0">
      <div class="flex-grow">
        <p class="text-sm font-medium text-gray-500" style="line-height: 24px;">Nominal Top Up</p>
        <p id="copyableText" class="text-lg font-semibold text-gray-900" style="line-height: 24px; font-size: 14px;">{{ $amount }}</p>
      </div>
      <button 
          id="copy-amount"
          type="button" 
          class="ml-auto text-blue-500 hover:text-blue-700 copy-amount" style="font-size: 14px; line-height: 24px;"
          onclick="copyToClipboardAmount()">
          Copy
      </button>
      <span id="copy-amount-feedback" class="ml-auto hidden" style="color: green; font-size: 14px; line-height: 24px;">Copied!</span>
    </div>
</div>

<script>
    function copyToClipboardAmount() {
        const text = document.getElementById('copyableText').innerText; // Menggunakan ID yang benar
        navigator.clipboard.writeText(text).then(function() {
            // Menyembunyikan tombol Copy dan menampilkan feedback "Copied"
            const copyButton = document.getElementById('copy-amount');
            const feedback = document.getElementById('copy-amount-feedback');
            
            copyButton.classList.add('hidden');  // Menyembunyikan tombol Copy
            feedback.classList.remove('hidden');  // Menampilkan feedback

            // Menampilkan feedback "Copied" selama 2000ms
            setTimeout(() => {
                copyButton.classList.remove('hidden');  // Menampilkan kembali tombol Copy
                feedback.classList.add('hidden');  // Menyembunyikan feedback setelah 2000ms
            }, 2000);
        }, function(err) {
            console.error('Failed to copy text: ', err);
        });
    }
</script>
