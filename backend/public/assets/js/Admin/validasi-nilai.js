let currentLockKelas = "";
let currentLockRombelId = 0;

// --- DRAWER DETAIL KELAS ---
// --- DRAWER DETAIL KELAS ---
window.showDetailValidasi = function(rombelId, kelas, wali) {
  const drawer = document.getElementById("detailDrawer");
  const overlay = document.getElementById("detailDrawerOverlay");
  const header = document.getElementById("drawerHeader");
  const mapelList = document.getElementById("drawerMapelList");

  // Reset isi laci
  header.innerHTML = `
    <div class="flex items-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800/50 shadow-sm">
      <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-inner">
        ${kelas.substring(0,2).toUpperCase()}
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-bold text-blue-900 dark:text-blue-400 truncate">Kelas ${kelas}</p>
        <p class="text-sm text-blue-700 dark:text-blue-300 truncate">Wali: ${wali}</p>
      </div>
    </div>
  `;
  mapelList.innerHTML = `
    <div class="flex flex-col items-center justify-center py-10">
        <svg class="animate-spin h-8 w-8 text-blue-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <p class="text-sm text-gray-500 font-medium">Menarik data dari server...</p>
    </div>`;

  // MUNCULKAN LACI (Animasi Tailwind)
  if (overlay) {
      overlay.classList.remove('hidden');
      setTimeout(() => overlay.classList.remove('opacity-0'), 10);
  }
  if (drawer) {
      drawer.classList.remove('translate-x-full');
  }
  document.body.style.overflow = "hidden";

  // Ambil Data Mapel & Progress via AJAX
  fetch(window.BASE_URL + 'admin/validasi-nilai/detail/' + rombelId, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(res => {
      if (!res.ok) throw new Error("Gagal mengambil data");
      return res.json();
  })
  .then(data => {
      if(data.status === 'success') {
          if(data.detail_mapel.length === 0) {
              mapelList.innerHTML = `<div class="p-4 bg-amber-50 text-amber-700 rounded-xl text-center text-sm font-medium border border-amber-200">Belum ada Guru yang ditugaskan ke kelas ini.</div>`;
              return;
          }

          let html = '';
          data.detail_mapel.forEach(m => {
              let color = m.progress === 100 ? 'emerald' : 'amber';
              let icon = m.progress === 100 
                  ? `<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>` 
                  : `<svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`;

              html += `
                  <div class="p-4 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-sm hover:border-${color}-300 transition-colors">
                      <div class="flex justify-between items-start mb-3">
                          <div>
                              <p class="font-bold text-gray-800 dark:text-white text-sm">${m.mapel}</p>
                              <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">${m.guru}</p>
                          </div>
                          <div class="bg-${color}-50 dark:bg-${color}-900/20 p-1.5 rounded-lg border border-${color}-100 dark:border-${color}-800/50 shadow-sm">
                              ${icon}
                          </div>
                      </div>
                      <div class="flex items-center gap-3">
                          <div class="flex-1 h-2 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden shadow-inner border border-gray-200 dark:border-slate-600">
                              <div class="h-full bg-${color}-500 rounded-full" style="width: ${m.progress}%"></div>
                          </div>
                          <span class="text-[11px] font-black text-${color}-600 dark:text-${color}-400">${m.progress}%</span>
                      </div>
                  </div>
              `;
          });
          mapelList.innerHTML = html;
      } else {
          mapelList.innerHTML = `<p class="text-sm text-red-500 text-center py-4">${data.message}</p>`;
      }
  }).catch(err => {
      console.error(err);
      mapelList.innerHTML = `<div class="p-4 bg-red-50 text-red-700 rounded-xl text-center text-sm font-medium border border-red-200">Gagal memuat data detail. Periksa koneksi atau Route Anda.</div>`;
  });
};

window.closeDetailDrawer = function() {
  const drawer = document.getElementById("detailDrawer");
  const overlay = document.getElementById("detailDrawerOverlay");
  
  if (drawer) drawer.classList.add("translate-x-full");
  if (overlay) overlay.classList.add("opacity-0");
  
  setTimeout(() => {
      if (overlay) overlay.classList.add("hidden");
      document.body.style.overflow = "";
  }, 300);
};

// --- MODAL LOCK (SATUAN) ---
window.showLockModal = function(kelasNama, rombelId) {
  currentLockKelas = kelasNama;
  currentLockRombelId = rombelId;

  const modal = document.getElementById("lockModal");
  const kelasSpan = document.getElementById("lockKelas");
  const checkbox = document.getElementById("confirmCheck");

  if (kelasSpan) kelasSpan.textContent = kelasNama;
  if (checkbox) checkbox.checked = false;
  
  if (modal) {
      modal.classList.remove("hidden"); 
      modal.classList.add("active"); 
  }
};

window.closeLockModal = function() {
  const modal = document.getElementById("lockModal");
  if (modal) {
      modal.classList.remove("active");
      modal.classList.add("hidden");
  }
  currentLockKelas = "";
};

window.confirmLock = function() {
  const checkbox = document.getElementById("confirmCheck");
  if (!checkbox || !checkbox.checked) {
    Swal.fire({ icon: "warning", title: "Tunggu dulu", text: "Centang kotak konfirmasi terlebih dahulu!", confirmButtonColor: "#F59E0B" });
    return;
  }

  let formData = new FormData();
  formData.append("rombel_id", currentLockRombelId);
  if (window.CSRF_NAME && window.CSRF_TOKEN) { formData.append(window.CSRF_NAME, window.CSRF_TOKEN); }

  const btn = document.querySelector("#lockModal button.bg-emerald-600");
  const originalText = btn ? btn.innerHTML : "Eksekusi";
  if (btn) { btn.innerHTML = "Memproses..."; btn.disabled = true; }

  fetch(window.BASE_URL + "admin/validasi-nilai/prosesLock", {
    method: "POST", body: formData, headers: { "X-Requested-With": "XMLHttpRequest" }
  })
  .then(res => res.json())
  .then(data => {
      window.closeLockModal();
      if (data.status === "success") {
        Swal.fire({ title: "Terkunci!", text: data.message, icon: "success", confirmButtonColor: "#10B981" }).then(() => window.location.reload());
      } else {
        Swal.fire({ icon: "error", title: data.title || "Gagal", html: data.message });
      }
  })
  .catch(err => {
      window.closeLockModal();
      Swal.fire({ icon: "error", title: "Sistem Error", text: "Gagal terhubung ke server." });
  });
};

// --- BUKA KUNCI (UNLOCK / NONAKTIFKAN) ---
window.unlockKelas = function(kelasNama, rombelId) {
    Swal.fire({
        title: 'Buka Kunci Kelas?',
        html: `Anda akan membuka kunci nilai untuk kelas <b>${kelasNama}</b>. <br>Guru akan bisa mengubah nilai kembali.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Buka Kunci!'
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append("rombel_id", rombelId);
            if (window.CSRF_NAME && window.CSRF_TOKEN) { formData.append(window.CSRF_NAME, window.CSRF_TOKEN); }

            fetch(window.BASE_URL + "admin/validasi-nilai/unlock", {
                method: "POST", body: formData, headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    Swal.fire("Berhasil!", data.message, "success").then(() => window.location.reload());
                } else {
                    Swal.fire("Gagal", data.message, "error");
                }
            });
        }
    });
};

// --- VALIDASI MASSAL ---
window.validasiMassal = function() {
  const rows = document.querySelectorAll('#validasiTable tbody tr');
  let siap = 0, belum = 0, terkunci = 0;

  rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      if (text.includes('siap validasi')) siap++;
      else if (text.includes('belum lengkap')) belum++;
      else if (text.includes('locked') || text.includes('terkunci')) terkunci++;
  });

  Swal.fire({
      title: 'Hasil Scan Massal',
      html: `
          <div class="text-left space-y-3 mt-4">
              <div class="p-3 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 flex justify-between items-center">
                  <span>✅ <b>${siap} Kelas</b> Siap Kunci</span>
              </div>
              <div class="p-3 bg-amber-50 text-amber-700 rounded-xl border border-amber-200 flex justify-between items-center">
                  <span>⚠️ <b>${belum} Kelas</b> Belum Lengkap</span>
              </div>
              <div class="p-3 bg-gray-50 text-gray-700 rounded-xl border border-gray-200 flex justify-between items-center">
                  <span>🔒 <b>${terkunci} Kelas</b> Sudah Terkunci</span>
              </div>
          </div>
      `,
      icon: 'info',
      confirmButtonText: 'Tutup'
  });
};

// --- LOCK MASSAL ---
window.lockNilaiMassal = function() {
  Swal.fire({
      title: 'Eksekusi Lock Massal?',
      text: "Sistem akan mengunci otomatis semua kelas yang progressnya 100%.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#10b981',
      cancelButtonColor: '#94a3b8',
      confirmButtonText: 'Ya, Kunci Semua!'
  }).then((result) => {
      if (result.isConfirmed) {
          Swal.fire({ title: 'Memproses...', text: 'Mengecek seluruh kelas...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() }});

          let formData = new FormData();
          if (window.CSRF_NAME && window.CSRF_TOKEN) { formData.append(window.CSRF_NAME, window.CSRF_TOKEN); }
          
          fetch(window.BASE_URL + 'admin/validasi-nilai/lockMassal', {
              method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'}
          })
          .then(res => res.json())
          .then(data => {
              if(data.status === 'success') {
                  Swal.fire({ title: 'Selesai!', html: data.message, icon: 'success' }).then(() => window.location.reload());
              } else {
                  Swal.fire('Gagal', data.message, 'error');
              }
          });
      }
  });
};

// --- FILTERING ---
function applyFilter() {
  const tingkat = document.getElementById("filterTingkat").value;
  const rombel = document.getElementById("filterRombel").value;
  const wali = document.getElementById("filterWali").value;
  const status = document.getElementById("filterStatus").value;

  const params = new URLSearchParams();
  if (tingkat) params.append("tingkat", tingkat);
  if (rombel) params.append("rombel", rombel);
  if (wali) params.append("wali", wali);
  if (status) params.append("status", status);

  window.location.href = window.location.pathname + "?" + params.toString();
}

function resetFilter() {
  window.location.href = window.location.pathname;
}

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    closeDetailDrawer();
    closeLockModal();
  }
});