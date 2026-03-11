
    // Data Catatan Rapor
    let catatanRaporData = [
      { id: 1, nama: 'Ahmad Ridho', status: 'Proses', catatan: 'Siswa menunjukkan peningkatan prestasi yang signifikan dalam mata pelajaran Matematika. Perlu dorongan untuk konsisten dalam persiapan ujian akhir.', rekomendasi: 'Bimbingan rutin untuk mengasah kemampuan problem solving', createdAt: '2024-01-18', updatedAt: '2024-01-19' },
      { id: 2, nama: 'Siti Nur Azizah', status: 'Selesai', catatan: 'Siswa berprestasi dengan konsisten mencapai nilai tertinggi di kelas. Menunjukkan dedikasi tinggi dalam pembelajaran dan kepemimpinan yang kuat. Layak mendapat penghargaan.', rekomendasi: 'Pertahankan prestasi dan tingkatkan kontribusi dalam kegiatan sekolah', createdAt: '2024-01-17', updatedAt: '2024-01-19' },
      { id: 3, nama: 'Muhammad Rizki', status: 'Proses', catatan: 'Siswa mengalami kesulitan dalam beberapa mata pelajaran terutama IPA dan Matematika. Perlu bimbingan khusus dan pendampingan lebih intensif.', rekomendasi: 'Program remedial dan pendampingan belajar tambahan', createdAt: '2024-01-16', updatedAt: '2024-01-18' },
      { id: 4, nama: 'Nadia Putri', status: 'Selesai', catatan: 'Siswa menunjukkan performa akademik yang sangat baik dengan sikap yang positif dan responsif terhadap pembelajaran. Menjadi role model bagi teman-temannya.', rekomendasi: 'Libatkan sebagai tutor sebaya untuk membantu siswa lain', createdAt: '2024-01-15', updatedAt: '2024-01-19' },
      { id: 5, nama: 'Fajar Hamdani', status: 'Proses', catatan: 'Siswa menunjukkan peningkatan namun masih perlu perhatian khusus dalam disiplin dan kehadiran. Sikap kooperatif sudah lebih baik dari semester sebelumnya.', rekomendasi: 'Komunikasi dengan orang tua mengenai kehadiran dan kedisiplinan', createdAt: '2024-01-14', updatedAt: '2024-01-17' },
      { id: 6, nama: 'Lina Marlina', status: 'Selesai', catatan: 'Siswa berprestasi dengan nilai yang konsisten tinggi dan menunjukkan karakter positif. Aktif dalam kegiatan ekstrakurikuler dan memiliki inisiatif.', rekomendasi: 'Dukung partisipasi dalam kompetisi akademik tingkat lebih tinggi', createdAt: '2024-01-13', updatedAt: '2024-01-19' }
    ];

    // Navigate functions
    function goToProgresTahfidz(event) {
      if (event) event.preventDefault();
      renderProgresTahfidz();
    }

    function goToPreviewRapor(event) {
      if (event) event.preventDefault();
      renderPreviewRapor();
    }

    function goToCatatanRapor(event) {
      if (event) event.preventDefault();
      renderCatatanRapor();
    }

    // Render Catatan Rapor Page
    function renderCatatanRapor() {
      updateActiveMenu('Catatan Rapor Wali');
      const mainContent = document.getElementById('mainContent');
      const selesai = catatanRaporData.filter(c => c.status === 'Selesai').length;
      const proses = catatanRaporData.filter(c => c.status === 'Proses').length;

      mainContent.innerHTML = `
        <!-- Header Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
          <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-xs lg:text-sm text-gray-500 mb-1">Total Siswa</p>
                <p class="text-2xl lg:text-3xl font-bold text-gray-800">${catatanRaporData.length}</p>
              </div>
              <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3.6a1.6 1.6 0 01-1.6-1.6V5.6A1.6 1.6 0 013.6 4h12.8a1.6 1.6 0 011.6 1.6v12.8a1.6 1.6 0 01-1.6 1.6z"/>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-xs lg:text-sm text-gray-500 mb-1">Selesai</p>
                <p class="text-2xl lg:text-3xl font-bold text-emerald-600">${selesai}</p>
              </div>
              <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-xs lg:text-sm text-gray-500 mb-1">Proses</p>
                <p class="text-2xl lg:text-3xl font-bold text-amber-600">${proses}</p>
              </div>
              <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-xs lg:text-sm text-gray-500 mb-1">Persentase Selesai</p>
                <p class="text-2xl lg:text-3xl font-bold text-purple-600">${Math.round((selesai / catatanRaporData.length) * 100)}%</p>
              </div>
              <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <input type="text" id="searchCatatan" placeholder="Cari nama siswa..." class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" onkeyup="filterCatatan()">
            <select id="filterStatusCatatan" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" onchange="filterCatatan()">
              <option value="">Semua Status</option>
              <option value="Selesai">Selesai</option>
              <option value="Proses">Proses</option>
            </select>
            <select id="sortCatatan" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500" onchange="filterCatatan()">
              <option value="terbaru">Update Terbaru</option>
              <option value="nama">Nama A-Z</option>
              <option value="status">Status</option>
            </select>
            <button onclick="openTambahCatatan()" class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 text-sm flex items-center justify-center gap-2 whitespace-nowrap">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Tambah Catatan
            </button>
          </div>
        </div>

        <!-- Catatan Cards -->
        <div id="catatanList" class="space-y-4"></div>
      `;

      renderCatatanList();
    }

    // Render Catatan List
    function renderCatatanList() {
      const container = document.getElementById('catatanList');
      if (!container) return;

      container.innerHTML = catatanRaporData.map(catatan => {
        const statusBg = catatan.status === 'Selesai' ? 'bg-emerald-100' : 'bg-amber-100';
        const statusText = catatan.status === 'Selesai' ? 'text-emerald-700' : 'text-amber-700';
        const statusIcon = catatan.status === 'Selesai' ? '✓' : '⏳';

        return `
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 card-hover">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4 pb-4 border-b border-gray-100">
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                  <h3 class="text-xl font-bold text-gray-800">${catatan.nama}</h3>
                  <span class="px-3 py-1 text-xs font-bold rounded-full ${statusBg} ${statusText}">${statusIcon} ${catatan.status}</span>
                </div>
                <p class="text-xs text-gray-500">
                  📅 Dibuat: ${new Date(catatan.createdAt).toLocaleDateString('id-ID')} | 
                  ✏️ Update: ${new Date(catatan.updatedAt).toLocaleDateString('id-ID')}
                </p>
              </div>
              <div class="flex items-center gap-2 flex-wrap">
                ${catatan.status === 'Proses' ? `<button onclick="openEditCatatan(${catatan.id})" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700">Edit</button>` : ''}
                <button onclick="viewDetailCatatan(${catatan.id})" class="px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded-lg hover:bg-gray-700">Lihat Detail</button>
                ${catatan.status === 'Proses' ? `<button onclick="deleteCatatan(${catatan.id})" class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700">Hapus</button>` : ''}
              </div>
            </div>

            <!-- Catatan Preview -->
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
              <p class="text-sm text-gray-800 line-clamp-3">"${catatan.catatan}"</p>
            </div>

            <!-- Rekomendasi -->
            <div class="flex items-start gap-3 p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div class="flex-1">
                <p class="text-xs font-semibold text-blue-900 mb-1">Rekomendasi:</p>
                <p class="text-sm text-blue-800">${catatan.rekomendasi}</p>
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    // Filter & Sort
    function filterCatatan() {
      const search = document.getElementById('searchCatatan')?.value.toLowerCase() || '';
      const status = document.getElementById('filterStatusCatatan')?.value || '';
      const sort = document.getElementById('sortCatatan')?.value || 'terbaru';

      let filtered = catatanRaporData.filter(c => {
        let match = true;
        if (search && !c.nama.toLowerCase().includes(search)) match = false;
        if (status && c.status !== status) match = false;
        return match;
      });

      // Sorting
      if (sort === 'terbaru') {
        filtered.sort((a, b) => new Date(b.updatedAt) - new Date(a.updatedAt));
      } else if (sort === 'nama') {
        filtered.sort((a, b) => a.nama.localeCompare(b.nama));
      } else if (sort === 'status') {
        filtered.sort((a, b) => a.status.localeCompare(b.status));
      }

      const container = document.getElementById('catatanList');
      container.innerHTML = filtered.length === 0 
        ? '<div class="bg-white rounded-xl p-12 text-center"><p class="text-gray-500">Tidak ada data</p></div>'
        : filtered.map(catatan => {
          const statusBg = catatan.status === 'Selesai' ? 'bg-emerald-100' : 'bg-amber-100';
          const statusText = catatan.status === 'Selesai' ? 'text-emerald-700' : 'text-amber-700';
          const statusIcon = catatan.status === 'Selesai' ? '✓' : '⏳';

          return `
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 card-hover">
              <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-4 pb-4 border-b border-gray-100">
                <div class="flex-1">
                  <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-xl font-bold text-gray-800">${catatan.nama}</h3>
                    <span class="px-3 py-1 text-xs font-bold rounded-full ${statusBg} ${statusText}">${statusIcon} ${catatan.status}</span>
                  </div>
                  <p class="text-xs text-gray-500">
                    📅 Dibuat: ${new Date(catatan.createdAt).toLocaleDateString('id-ID')} | 
                    ✏️ Update: ${new Date(catatan.updatedAt).toLocaleDateString('id-ID')}
                  </p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                  ${catatan.status === 'Proses' ? `<button onclick="openEditCatatan(${catatan.id})" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700">Edit</button>` : ''}
                  <button onclick="viewDetailCatatan(${catatan.id})" class="px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded-lg hover:bg-gray-700">Lihat Detail</button>
                  ${catatan.status === 'Proses' ? `<button onclick="deleteCatatan(${catatan.id})" class="px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700">Hapus</button>` : ''}
                </div>
              </div>

              <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-800 line-clamp-3">"${catatan.catatan}"</p>
              </div>

              <div class="flex items-start gap-3 p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                  <p class="text-xs font-semibold text-blue-900 mb-1">Rekomendasi:</p>
                  <p class="text-sm text-blue-800">${catatan.rekomendasi}</p>
                </div>
              </div>
            </div>
          `;
        }).join('');
    }

    // Open Tambah Catatan Modal
    function openTambahCatatan() {
      const modal = document.createElement('div');
      modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 overflow-y-auto';
      modal.innerHTML = `
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full my-auto">
          <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4 flex items-center justify-between text-white">
            <h2 class="text-xl font-bold">Tambah Catatan Rapor</h2>
            <button onclick="this.closest('div').parentElement.remove()" class="p-2 hover:bg-emerald-500 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <div class="p-6 max-h-96 overflow-y-auto">
            <form onsubmit="submitTambahCatatan(event)">
              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Pilih Siswa</label>
                <select id="siswaSelectCatatan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                  <option value="">Pilih Siswa</option>
                  ${catatanRaporData.map(c => `<option value="${c.id}">${c.nama}</option>`).join('')}
                </select>
              </div>

              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Catatan Rapor</label>
                <textarea id="catatanText" required placeholder="Tulis catatan perkembangan siswa..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none" rows="5"></textarea>
              </div>

              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Rekomendasi</label>
                <textarea id="rekomendasiText" required placeholder="Tulis rekomendasi pembinaan..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none" rows="3"></textarea>
              </div>

              <div class="flex justify-end gap-3">
                <button type="button" onclick="this.closest('div').parentElement.remove()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      `;
      document.body.appendChild(modal);
      modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });
    }

    // Submit Tambah Catatan
    function submitTambahCatatan(event) {
      event.preventDefault();
      const siswaId = parseInt(document.getElementById('siswaSelectCatatan').value);
      const siswa = catatanRaporData.find(c => c.id === siswaId);

      if (!siswa) return;

      siswa.catatan = document.getElementById('catatanText').value;
      siswa.rekomendasi = document.getElementById('rekomendasiText').value;
      siswa.status = 'Proses';
      siswa.updatedAt = new Date().toISOString().split('T')[0];

      document.querySelector('[onclick*="openTambahCatatan"]')?.closest('div')?.parentElement?.remove();
      renderCatatanRapor();

      const notification = document.createElement('div');
      notification.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-40';
      notification.innerHTML = `<p class="font-semibold">✓ Catatan untuk ${siswa.nama} ditambahkan</p>`;
      document.body.appendChild(notification);
      setTimeout(() => notification.remove(), 3000);
    }

    // Open Edit Catatan
    function openEditCatatan(id) {
      const catatan = catatanRaporData.find(c => c.id === id);
      if (!catatan) return;

      const modal = document.createElement('div');
      modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 overflow-y-auto';
      modal.innerHTML = `
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full my-auto">
          <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4 flex items-center justify-between text-white">
            <h2 class="text-xl font-bold">Edit Catatan Rapor</h2>
            <button onclick="this.closest('div').parentElement.remove()" class="p-2 hover:bg-blue-500 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <div class="p-6 max-h-96 overflow-y-auto">
            <form onsubmit="submitEditCatatan(event, ${id})">
              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Nama Siswa</label>
                <input type="text" value="${catatan.nama}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
              </div>

              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Catatan Rapor</label>
                <textarea id="editCatatanText" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="5">${catatan.catatan}</textarea>
              </div>

              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Rekomendasi</label>
                <textarea id="editRekomendasiText" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="3">${catatan.rekomendasi}</textarea>
              </div>

              <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-800 mb-2">Status</label>
                <select id="editStatusCatatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                  <option value="Proses" ${catatan.status === 'Proses' ? 'selected' : ''}>Proses</option>
                  <option value="Selesai" ${catatan.status === 'Selesai' ? 'selected' : ''}>Selesai</option>
                </select>
              </div>

              <div class="flex justify-end gap-3">
                <button type="button" onclick="this.closest('div').parentElement.remove()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">Batal</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      `;
      document.body.appendChild(modal);
      modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });
    }

    // Submit Edit Catatan
    function submitEditCatatan(event, id) {
      event.preventDefault();
      const catatan = catatanRaporData.find(c => c.id === id);

      if (!catatan) return;

      catatan.catatan = document.getElementById('editCatatanText').value;
      catatan.rekomendasi = document.getElementById('editRekomendasiText').value;
      catatan.status = document.getElementById('editStatusCatatan').value;
      catatan.updatedAt = new Date().toISOString().split('T')[0];

      document.querySelector('form')?.closest('div')?.parentElement?.remove();
      renderCatatanRapor();

      const notification = document.createElement('div');
      notification.className = 'fixed bottom-4 right-4 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg z-40';
      notification.innerHTML = `<p class="font-semibold">✓ Catatan ${catatan.nama} diperbarui</p>`;
      document.body.appendChild(notification);
      setTimeout(() => notification.remove(), 3000);
    }

    // View Detail Catatan
    function viewDetailCatatan(id) {
      const catatan = catatanRaporData.find(c => c.id === id);
      if (!catatan) return;

      const modal = document.createElement('div');
      modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 overflow-y-auto';
      modal.innerHTML = `
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full my-auto">
          <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4 flex items-center justify-between text-white">
            <h2 class="text-xl font-bold">Detail Catatan Rapor</h2>
            <button onclick="this.closest('div').parentElement.remove()" class="p-2 hover:bg-purple-500 rounded-lg">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
          <div class="p-6 space-y-6">
            <div>
              <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-2">Nama Siswa</p>
              <p class="text-2xl font-bold text-gray-800">${catatan.nama}</p>
            </div>

            <div>
              <div class="flex items-center justify-between mb-3">
                <p class="text-xs text-gray-600 uppercase tracking-widest font-bold">Status</p>
                <span class="px-3 py-1 text-xs font-bold rounded-full ${catatan.status === 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'}">
                  ${catatan.status === 'Selesai' ? '✓' : '⏳'} ${catatan.status}
                </span>
              </div>
            </div>

            <div>
              <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-3">Catatan Rapor</p>
              <div class="p-4 bg-gray-50 border-l-4 border-purple-500 rounded">
                <p class="text-gray-800 leading-relaxed">${catatan.catatan}</p>
              </div>
            </div>

            <div>
              <p class="text-xs text-gray-600 uppercase tracking-widest font-bold mb-3">Rekomendasi</p>
              <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                <p class="text-gray-800 leading-relaxed">${catatan.rekomendasi}</p>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
              <div>
                <p class="text-xs text-gray-600 mb-1">Dibuat</p>
                <p class="font-semibold text-gray-800">${new Date(catatan.createdAt).toLocaleDateString('id-ID')}</p>
              </div>
              <div>
                <p class="text-xs text-gray-600 mb-1">Diperbarui</p>
                <p class="font-semibold text-gray-800">${new Date(catatan.updatedAt).toLocaleDateString('id-ID')}</p>
              </div>
            </div>

            <button onclick="this.closest('div').parentElement.remove()" class="w-full mt-6 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Tutup</button>
          </div>
        </div>
      `;
      document.body.appendChild(modal);
      modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });
    }

    // Delete Catatan
    function deleteCatatan(id) {
      if (confirm('Yakin ingin menghapus catatan ini?')) {
        const catatan = catatanRaporData.find(c => c.id === id);
        catatanRaporData = catatanRaporData.filter(c => c.id !== id);
        renderCatatanRapor();

        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-40';
        notification.innerHTML = `<p class="font-semibold">✓ Catatan ${catatan.nama} dihapus</p>`;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
      }
    }

    // Render Progres Tahfidz (placeholder)
    function renderProgresTahfidz() {
      updateActiveMenu('Progres Tahfidz');
      document.getElementById('mainContent').innerHTML = '<div class="p-12 text-center text-gray-500"><p>Halaman Progres Tahfidz</p></div>';
    }

    // Render Preview Rapor (placeholder)
    function renderPreviewRapor() {
      updateActiveMenu('Preview Rapor Kelas');
      document.getElementById('mainContent').innerHTML = '<div class="p-12 text-center text-gray-500"><p>Halaman Preview Rapor Kelas</p></div>';
    }

    // Update Active Menu
    function updateActiveMenu(page) {
      document.getElementById('pageTitle').textContent = page === 'Progres Tahfidz' ? '📖 Progres Tahfidz' : page === 'Preview Rapor Kelas' ? '📋 Preview Rapor Siswa' : '📝 Catatan Rapor Wali';
    }

    // Toggle Sidebar
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('-translate-x-full');
    }

    // Toggle Accordion
    function toggleAccordion(id) {
      const content = document.getElementById(`accordion-${id}`);
      const arrow = document.getElementById(`arrow-${id}`);
      content.classList.toggle('open');
      arrow.classList.toggle('open');
    }

    // Initialize
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => {
        renderCatatanRapor();
      });
    } else {
      renderCatatanRapor();
    }(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9c7620eff2b3fcfa',t:'MTc2OTk5Nzk4MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();
