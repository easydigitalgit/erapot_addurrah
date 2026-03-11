// ==========================================
// SABUK PENGAMAN (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    js_loading: 'Memuat data dari database...',
    js_err_load: 'Gagal memuat data',
    js_err_net: 'Terjadi kesalahan jaringan',
    js_empty_data: 'Belum ada data nilai terkunci untuk kelas ini.',
    js_show_num: 'Nilai angka ditampilkan',
    js_hide_num: 'Nilai angka disembunyikan',
    js_show_pred: 'Predikat ditampilkan',
    js_hide_pred: 'Predikat disembunyikan',
    js_preparing: 'Sedang disiapkan...',
    js_preparing_print: 'Menyiapkan dokumen untuk dicetak...'
};

let currentLegerData = [];
let currentSortMode = 'abjad'; 

function hitungRataRata(siswa) {
  const total = siswa.pai + siswa.bindo + siswa.barab + siswa.bing + siswa.mtk + siswa.ipa + siswa.ips + siswa.tahfidz;
  return (total / 8).toFixed(1);
}

function loadLegerData() {
  const tbody = document.getElementById('legerTableBody');
  tbody.innerHTML = `<tr><td colspan="19" class="text-center py-4">${LANG.js_loading}</td></tr>`;

  const formData = new FormData();
  formData.append('tahun_ajaran', '2025/2026');
  formData.append('semester', 'Genap');
  formData.append('rombel_id', '10'); 

  fetch('/raporsmpit/admin/cetak-leger/get-data', {
      method: 'POST',
      body: formData,
      headers: {
          "X-Requested-With": "XMLHttpRequest"
      }
  })
  .then(response => response.json())
  .then(res => {
      if (res.status === 'success') {
          currentLegerData = res.data; 
          renderTable(currentLegerData);
      } else {
          tbody.innerHTML = `<tr><td colspan="19" class="text-center py-4 text-red-500">${LANG.js_err_load}</td></tr>`;
      }
  })
  .catch(err => {
      console.error(err);
      tbody.innerHTML = `<tr><td colspan="19" class="text-center py-4 text-red-500">${LANG.js_err_net}</td></tr>`;
  });
}

function changeSortMode() {
    currentSortMode = document.getElementById('sort_mode').value;
    if(currentLegerData.length > 0) {
        renderTable(currentLegerData);
    }
}

function renderTable(dataSiswa) {
    const tbody = document.getElementById('legerTableBody');
    tbody.innerHTML = '';

    if(dataSiswa.length === 0) {
        tbody.innerHTML = `<tr><td colspan="19" class="text-center py-4">${LANG.js_empty_data}</td></tr>`;
        return;
    }
  
    let processedData = dataSiswa.map(s => ({
        ...s,
        avg: parseFloat(hitungRataRata(s))
    }));

    processedData.sort((a, b) => b.avg - a.avg);
    processedData.forEach((siswa, index) => {
        siswa.rank = index + 1;
    });

    updateStatisticsCards(processedData);

    if (currentSortMode === 'abjad') {
        processedData.sort((a, b) => a.nama.localeCompare(b.nama));
    } else {
        processedData.sort((a, b) => a.rank - b.rank);
    }  

  const subjects = ['pai', 'bindo', 'barab', 'bing', 'mtk', 'ipa', 'ips', 'tahfidz'];
  const highest = {};
  const lowest = {};
  
  subjects.forEach(subj => {
    const values = processedData.map(s => s[subj]).filter(v => v > 0); 
    if(values.length > 0) {
        highest[subj] = Math.max(...values);
        lowest[subj] = Math.min(...values);
    } else {
        highest[subj] = 0;
        lowest[subj] = 0;
    }
  });
  
  processedData.forEach((siswa, index) => {
    const rank = siswa.rank; 
    const isTopStudent = rank === 1;
    
    const row = document.createElement('tr');
    
    row.innerHTML = `
      <td>${index + 1}</td> <td>${siswa.nis}</td>
      <td class="text-left font-medium">${siswa.nama}</td>
      
      <td class="nilai-angka ${siswa.pai === highest.pai && siswa.pai > 0 ? 'highlight-highest' : siswa.pai === lowest.pai && siswa.pai > 0 ? 'highlight-lowest' : ''}">${siswa.pai}</td>
      <td class="nilai-predikat">${siswa.pai_pred}</td>
      
      <td class="nilai-angka ${siswa.bindo === highest.bindo && siswa.bindo > 0 ? 'highlight-highest' : siswa.bindo === lowest.bindo && siswa.bindo > 0 ? 'highlight-lowest' : ''}">${siswa.bindo}</td>
      <td class="nilai-predikat">${siswa.bindo_pred}</td>
      
      <td class="nilai-angka ${siswa.barab === highest.barab && siswa.barab > 0 ? 'highlight-highest' : siswa.barab === lowest.barab && siswa.barab > 0 ? 'highlight-lowest' : ''}">${siswa.barab}</td>
      <td class="nilai-predikat">${siswa.barab_pred}</td>
      
      <td class="nilai-angka ${siswa.bing === highest.bing && siswa.bing > 0 ? 'highlight-highest' : siswa.bing === lowest.bing && siswa.bing > 0 ? 'highlight-lowest' : ''}">${siswa.bing}</td>
      <td class="nilai-predikat">${siswa.bing_pred}</td>
      
      <td class="nilai-angka ${siswa.mtk === highest.mtk && siswa.mtk > 0 ? 'highlight-highest' : siswa.mtk === lowest.mtk && siswa.mtk > 0 ? 'highlight-lowest' : ''}">${siswa.mtk}</td>
      <td class="nilai-predikat">${siswa.mtk_pred}</td>
      
      <td class="nilai-angka ${siswa.ipa === highest.ipa && siswa.ipa > 0 ? 'highlight-highest' : siswa.ipa === lowest.ipa && siswa.ipa > 0 ? 'highlight-lowest' : ''}">${siswa.ipa}</td>
      <td class="nilai-predikat">${siswa.ipa_pred}</td>
      
      <td class="nilai-angka ${siswa.ips === highest.ips && siswa.ips > 0 ? 'highlight-highest' : siswa.ips === lowest.ips && siswa.ips > 0 ? 'highlight-lowest' : ''}">${siswa.ips}</td>
      <td class="nilai-predikat">${siswa.ips_pred}</td>
      
      <td class="nilai-angka ${siswa.tahfidz === highest.tahfidz && siswa.tahfidz > 0 ? 'highlight-highest' : siswa.tahfidz === lowest.tahfidz && siswa.tahfidz > 0 ? 'highlight-lowest' : ''}">${siswa.tahfidz}</td>
      <td class="nilai-predikat">${siswa.tahfidz_pred}</td>
      
      <td class="${isTopStudent ? 'highlight-top-student' : ''}" style="font-weight: 700;">${siswa.avg}</td>
      <td class="${isTopStudent ? 'highlight-top-student' : ''}" style="font-weight: 700; color: #059669;">${rank}</td>
    `;
    
    tbody.appendChild(row);
  });
}

function openMobileSidebar() { /* ... */ }
function closeMobileSidebar() { /* ... */ }

function updateStatisticsCards(dataSiswa) {
    const subjects = ['pai', 'bindo', 'barab', 'bing', 'mtk', 'ipa', 'ips', 'tahfidz'];
    
    const mapelNames = {
        pai: 'PAI', bindo: 'B. Indonesia', barab: 'B. Arab', bing: 'B. Inggris',
        mtk: 'Matematika', ipa: 'IPA', ips: 'IPS', tahfidz: 'Tahfidz'
    };

    let allValidScores = [];

    dataSiswa.forEach(siswa => {
        subjects.forEach(subj => {
            let nilai = parseFloat(siswa[subj]);
            if (nilai > 0) {
                allValidScores.push({
                    nama: siswa.nama,
                    mapel: mapelNames[subj],
                    nilai: nilai
                });
            }
        });
    });

    if (allValidScores.length === 0) {
        document.getElementById('stat_rata_kelas').innerText = '0';
        document.getElementById('bar_rata_kelas').style.width = '0%';
        document.getElementById('stat_nilai_tertinggi').innerText = '0';
        document.getElementById('text_nilai_tertinggi').innerText = '-';
        document.getElementById('stat_nilai_terendah').innerText = '0';
        document.getElementById('text_nilai_terendah').innerText = '-';
        document.getElementById('stat_ketuntasan').innerText = '0%';
        document.getElementById('bar_ketuntasan').style.width = '0%';
        return;
    }

    const totalNilai = allValidScores.reduce((sum, item) => sum + item.nilai, 0);
    const rataKelas = (totalNilai / allValidScores.length).toFixed(1);

    allValidScores.sort((a, b) => a.nilai - b.nilai);
    const terendah = allValidScores[0];
    const tertinggi = allValidScores[allValidScores.length - 1];

    const KKM = 75; 
    const tuntasCount = allValidScores.filter(item => item.nilai >= KKM).length;
    const persenTuntas = Math.round((tuntasCount / allValidScores.length) * 100);

    document.getElementById('stat_rata_kelas').innerText = rataKelas;
    document.getElementById('bar_rata_kelas').style.width = rataKelas + '%';

    document.getElementById('stat_nilai_tertinggi').innerText = tertinggi.nilai;
    document.getElementById('text_nilai_tertinggi').innerText = `${tertinggi.nama} - ${tertinggi.mapel}`;

    document.getElementById('stat_nilai_terendah').innerText = terendah.nilai;
    document.getElementById('text_nilai_terendah').innerText = `${terendah.nama} - ${terendah.mapel}`;

    document.getElementById('stat_ketuntasan').innerText = persenTuntas + '%';
    document.getElementById('bar_ketuntasan').style.width = persenTuntas + '%';
}

function toggleNilaiAngka() {
    const isChecked = event.target.checked;
    const cells = document.querySelectorAll('.nilai-angka');
    cells.forEach(cell => {
        cell.style.display = isChecked ? '' : 'none';
    });
    showToast(isChecked ? LANG.js_show_num : LANG.js_hide_num, 'success');
}

function togglePredikat() {
    const isChecked = event.target.checked;
    const cells = document.querySelectorAll('.nilai-predikat');
    cells.forEach(cell => {
        cell.style.display = isChecked ? '' : 'none';
    });
    showToast(isChecked ? LANG.js_show_pred : LANG.js_hide_pred, 'success');
}

function downloadPDF() { showToast(LANG.js_preparing, 'info'); }
function exportExcel() { showToast(LANG.js_preparing, 'info'); }

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white border border-gray-100 rounded-xl shadow-lg transition-all duration-300';
    
    const iconColor = type === 'success' ? 'text-emerald-600' : (type === 'error' ? 'text-red-600' : 'text-blue-600');
    const bgColor = type === 'success' ? 'bg-emerald-100' : (type === 'error' ? 'bg-red-100' : 'bg-blue-100');
    
    let iconPath = '';
    if(type === 'success') iconPath = 'M5 13l4 4L19 7';
    else if(type === 'error') iconPath = 'M6 18L18 6M6 6l12 12';
    else iconPath = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

    toast.innerHTML = `
        <div class="w-10 h-10 rounded-full flex items-center justify-center ${bgColor} flex-shrink-0">
            <svg class="w-6 h-6 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">${message}</p>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function printLeger() {
  showToast(LANG.js_preparing_print, 'success');
  document.querySelector('.print-footer').style.display = 'block';
  setTimeout(() => window.print(), 500);
}

window.addEventListener('beforeprint', () => { document.querySelector('.print-footer').style.display = 'block'; });
window.addEventListener('afterprint', () => { document.querySelector('.print-footer').style.display = 'none'; });

document.addEventListener('DOMContentLoaded', () => {
  loadLegerData();
});