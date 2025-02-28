import './bootstrap';
import 'sweetalert2/dist/sweetalert2.min.css';
import Swal from 'sweetalert2';

// Global SweetAlert Toast Configuration
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// Success Toast
window.showSuccess = (message) => {
    Toast.fire({
        icon: 'success',
        title: message
    });
}

// Error Toast
window.showError = (message) => {
    Toast.fire({
        icon: 'error',
        title: message
    });
}

// Warning Toast
window.showWarning = (message) => {
    Toast.fire({
        icon: 'warning',
        title: message
    });
}

// Confirmation Dialog
window.showConfirmation = (title, text, callback) => {
    Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-xl',
            title: 'text-lg font-semibold text-gray-800',
            htmlContainer: 'text-base text-gray-600',
            confirmButton: 'rounded-lg text-sm font-medium px-5 py-2.5',
            cancelButton: 'rounded-lg text-sm font-medium px-5 py-2.5'
        }
    }).then((result) => {
        if (result.isConfirmed && callback) {
            callback();
        }
    });
}

// Listen for Livewire events
document.addEventListener('livewire:initialized', () => {
    // Success messages
    Livewire.on('success', (message) => {
        showSuccess(message);
    });

    // Error messages
    Livewire.on('error', (message) => {
        showError(message);
    });

    // Warning messages
    Livewire.on('warning', (message) => {
        showWarning(message);
    });
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js')
        .then(function(registration) {
            console.log('Service Worker Registered');
        });
}
