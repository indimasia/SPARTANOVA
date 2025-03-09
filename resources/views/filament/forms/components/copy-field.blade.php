
<div class="bg-white shadow-sm rounded-lg w-full mt-4">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-2 sm:space-y-0">
      <div class="flex-grow">
        <p class="text-sm font-medium text-gray-500" style="line-height: 24px;">Transfer ke</p>
        <p id="bank_account" class="text-lg font-semibold text-gray-900" style="line-height: 24px; font-size: 14px;">{{ $bank_account }}</p>
      </div>
      <button 
          id="copy-button"
          type="button" 
          class="ml-auto text-blue-500 hover:text-blue-700 copy-button" style="font-size: 14px; line-height: 24px;"
          onclick="copyToClipboard('#bank_account')">
          Copy
      </button>
      <span id="copy-feedback" class="ml-auto hidden" style="color: green; font-size: 14px; line-height: 24px;">Copied!</span>
    </div>
</div>

<script>
    function copyToClipboard(selector) {
        const text = document.querySelector(selector).innerText;
        navigator.clipboard.writeText(text).then(function() {
            const copyButton = document.getElementById('copy-button');
            const feedback = document.getElementById('copy-feedback');
            
            copyButton.classList.add('hidden');
            feedback.classList.remove('hidden');

            setTimeout(() => {
                copyButton.classList.remove('hidden');
                feedback.classList.add('hidden');
            }, 2000);
        }, function(err) {
            console.error('Failed to copy text: ', err);
        });
    }
</script>
