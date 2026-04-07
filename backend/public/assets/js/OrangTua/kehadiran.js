/**
 * File: public/assets/js/OrangTua/kehadiran.js
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Inisialisasi efek animasi stagger pada elemen saat halaman dimuat
    const items = document.querySelectorAll('.stagger-item');
    items.forEach((item, index) => {
        // Tunda pemunculan tiap kotak agar terlihat berurutan dari atas ke bawah
        item.style.animationDelay = `${(index + 1) * 100}ms`;
    });

});