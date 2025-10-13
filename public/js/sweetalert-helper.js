/**
 * SweetAlert2 Helper Functions
 * Global functions for beautiful confirmation dialogs
 */

// Delete Confirmation
function confirmDelete(title, message, form) {
    Swal.fire({
        title: title || 'Hapus Data?',
        html: message || 'Apakah Anda yakin ingin menghapus data ini?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-2xl shadow-2xl',
            title: 'text-2xl font-bold text-gray-800',
            htmlContainer: 'text-gray-600',
            confirmButton: 'rounded-lg px-6 py-3 font-semibold shadow-lg hover:shadow-xl transition-all',
            cancelButton: 'rounded-lg px-6 py-3 font-semibold shadow-md hover:shadow-lg transition-all'
        },
        buttonsStyling: true,
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menghapus...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            form.submit();
        }
    });
}

// General Confirmation
function confirmAction(title, message, confirmText, confirmColor = '#3b82f6') {
    return Swal.fire({
        title: title || 'Konfirmasi',
        html: message || 'Apakah Anda yakin?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#6b7280',
        confirmButtonText: confirmText || '<i class="fas fa-check mr-2"></i>Ya, Lanjutkan!',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-2xl shadow-2xl',
            title: 'text-2xl font-bold text-gray-800',
            htmlContainer: 'text-gray-600',
            confirmButton: 'rounded-lg px-6 py-3 font-semibold shadow-lg hover:shadow-xl transition-all',
            cancelButton: 'rounded-lg px-6 py-3 font-semibold shadow-md hover:shadow-lg transition-all'
        },
        buttonsStyling: true,
        showClass: {
            popup: 'animate__animated animate__zoomIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__zoomOut animate__faster'
        }
    });
}

// Success Message
function showSuccess(title, message) {
    Swal.fire({
        title: title || 'Berhasil!',
        html: message || 'Operasi berhasil dilakukan',
        icon: 'success',
        confirmButtonColor: '#10b981',
        confirmButtonText: '<i class="fas fa-check mr-2"></i>OK',
        customClass: {
            popup: 'rounded-2xl shadow-2xl',
            title: 'text-2xl font-bold text-gray-800',
            htmlContainer: 'text-gray-600',
            confirmButton: 'rounded-lg px-6 py-3 font-semibold shadow-lg hover:shadow-xl transition-all'
        },
        showClass: {
            popup: 'animate__animated animate__bounceIn animate__faster'
        }
    });
}

// Error Message
function showError(title, message) {
    Swal.fire({
        title: title || 'Error!',
        html: message || 'Terjadi kesalahan',
        icon: 'error',
        confirmButtonColor: '#dc2626',
        confirmButtonText: '<i class="fas fa-times mr-2"></i>OK',
        customClass: {
            popup: 'rounded-2xl shadow-2xl',
            title: 'text-2xl font-bold text-gray-800',
            htmlContainer: 'text-gray-600',
            confirmButton: 'rounded-lg px-6 py-3 font-semibold shadow-lg hover:shadow-xl transition-all'
        },
        showClass: {
            popup: 'animate__animated animate__shakeX animate__faster'
        }
    });
}

// Loading
function showLoading(title, message) {
    Swal.fire({
        title: title || 'Memproses...',
        html: message || 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}
