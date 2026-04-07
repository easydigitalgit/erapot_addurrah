document.addEventListener('DOMContentLoaded', function() {
    // Fitur Download Rapor PDF (Sama dengan Dashboard agar Terintegrasi Penuh)
    const btnDownload = document.getElementById('btnDownloadRaporAkademik');
    
    if (btnDownload) {
        btnDownload.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Tampilkan SweetAlert Loading (Mempersiapkan Sejarah TA)
            Swal.fire({
                title: 'Sedang Memuat...',
                text: 'Mempersiapkan daftar periode rapor ananda...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Ambil Sejarah TA dari Controller
            fetch(`${BASE_URL}/orangtua/get-history-ta`)
            .then(response => response.json())
            .then(history => {
                // Buat Opsi Dropdown Tahun Ajaran
                let taOptions = '';
                history.forEach(h => {
                    const isActive = (h.status && h.status.toLowerCase() === 'aktif');
                    const isSelected = isActive ? 'selected' : '';
                    const activeLabel = isActive ? ' - (Aktif)' : '';
                    taOptions += `<option value="${h.id}" ${isSelected}>${h.tahun} (${h.semester})${activeLabel}</option>`;
                });

                // Deteksi Dark Mode (Dinamis)
                const isDarkMode = document.documentElement.classList.contains('dark');
                const swalBg = isDarkMode ? '#1e293b' : '#ffffff';
                const swalText = isDarkMode ? '#f1f5f9' : '#1e293b';
                const inputBg = isDarkMode ? '#334155' : '#f8fafc';
                const inputText = isDarkMode ? '#f1f5f9' : '#334155';
                const inputBorder = isDarkMode ? '#475569' : '#e2e8f0';

                // Tampilkan Modal Pilihan (Dinamis & Terintegrasi)
                Swal.fire({
                    background: swalBg,
                    color: swalText,
                    width: '380px',
                    title: '',
                    html: `
                        <div class="flex flex-col items-center pt-2">
                            <div class="w-12 h-12 rounded-full ${isDarkMode ? 'bg-blue-500/10 border-blue-500/20' : 'bg-blue-50 border-blue-100'} flex items-center justify-center text-blue-500 shadow-sm border mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <span class="text-xl font-black ${isDarkMode ? 'text-slate-50' : 'text-slate-800'} tracking-tight mb-1">Unduh E-Rapor Digital</span>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest italic mb-6">Silakan pilih periode laporan ananda</p>

                            <div class="w-full text-left space-y-4 px-1">
                                <div class="group">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 block ml-1 group-focus-within:text-blue-500 transition-colors">Pilih Tahun Ajaran & Semester</label>
                                    <div class="relative">
                                        <select id="swal-ta" style="background-color: ${inputBg}; color: ${inputText}; border-color: ${inputBorder};" class="w-full p-4 rounded-2xl border text-sm font-semibold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                                            ${taOptions}
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="group">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 block ml-1 group-focus-within:text-blue-500 transition-colors">Pilih Jenis Laporan</label>
                                    <div class="relative">
                                        <select id="swal-tipe" style="background-color: ${inputBg}; color: ${inputText}; border-color: ${inputBorder};" class="w-full p-4 rounded-2xl border text-sm font-semibold focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                                            <option value="Akhir Semester">Rapor Akhir Semester (SAS)</option>
                                            <option value="Mid Semester">Rapor Mid Semester (STS)</option>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                    <p class="text-[9px] text-slate-400 mt-2 ml-1 italic font-medium">*Pastikan nilai sudah divalidasi oleh sekolah agar bisa diunduh.</p>
                                </div>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'MULAI UNDUH PDF',
                    cancelButtonText: 'BATAL',
                    confirmButtonColor: WARNA_PRIMARY, // Gunakan warna sekolah dinamis
                    cancelButtonColor: isDarkMode ? '#334155' : '#cbd5e1',
                    customClass: {
                        popup: 'rounded-[1.5rem]',
                        confirmButton: 'rounded-xl font-bold px-6 py-3',
                        cancelButton: 'rounded-xl font-bold px-6 py-3'
                    },
                    preConfirm: () => {
                        return {
                            ta_id: document.getElementById('swal-ta').value,
                            tipe: document.getElementById('swal-tipe').value
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { ta_id, tipe } = result.value;

                        // 3. Cek Ketersediaan secara dinamis
                        Swal.fire({
                            title: 'Mengecek Rapor...',
                            text: 'Mohon tunggu sebentar...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        fetch(`${BASE_URL}/orangtua/rapor/cek-ketersediaan?ta=${ta_id}&tipe=${tipe}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.close();
                                window.open(`${BASE_URL}/orangtua/rapor/download?ta=${ta_id}&tipe=${tipe}`, '_blank');
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Maaf, Belum Tersedia',
                                    text: data.message,
                                    confirmButtonColor: WARNA_PRIMARY
                                });
                            }
                        });
                    }
                });
            })
            .catch(error => {
                console.error("Gagal memuat data sejarah TA:", error);
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal menghubungi server sekolah!', confirmButtonColor: WARNA_PRIMARY });
            });
        });
    }
});