/**
 * File: public/assets/js/OrangTua/tahfidz.js
 */

document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi Animasi Stagger
    const items = document.querySelectorAll('.stagger-item');
    items.forEach((item, index) => {
        // Memberikan delay dinamis untuk setiap item agar muncul satu per satu
        item.style.animationDelay = `${(index + 1) * 80}ms`;
    });
});

// Fitur Live Search untuk mencari Surah di Riwayat Setoran
function filterRiwayat() {
    const input = document.getElementById("searchSetoran");
    if(!input) return;
    
    const filter = input.value.toLowerCase();
    const nodes = document.getElementsByClassName('riwayat-row');
    let visibleCount = 0;

    for (let i = 0; i < nodes.length; i++) {
        let surahName = nodes[i].querySelector('.surah-name').innerText.toLowerCase();
        
        if (surahName.includes(filter)) {
            nodes[i].style.display = "";
            visibleCount++;
        } else {
            nodes[i].style.display = "none";
        }
    }
    
    const noResult = document.getElementById('noResult');
    if (noResult) {
        noResult.style.display = visibleCount === 0 ? "block" : "none";
    }
}