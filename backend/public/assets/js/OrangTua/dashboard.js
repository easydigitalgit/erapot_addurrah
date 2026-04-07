/**
 * File: public/assets/js/OrangTua/dashboard.js
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Fitur Download Rapor PDF dengan Pilihan (Dinamis)
    const btnDownload = document.getElementById('btnDownloadRapor');
    
    if (btnDownload) {
        btnDownload.addEventListener('click', function() {
            
            // 1. Loading Awal (Ambil Sejarah Tahun Ajaran)
            Swal.fire({
                title: 'Memuat Data',
                text: 'Sedang mengambil riwayat akademik ananda...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Ambil Sejarah TA dari Controller
            fetch(`${BASE_URL}/orangtua/get-history-ta`)
            .then(response => response.json())
            .then(history => {
                if (history.length === 0) {
                    Swal.fire('Informasi', 'Belum ada data riwayat akademik yang tersedia.', 'info');
                    return;
                }

                // Buat Opsi Dropdown Tahun Ajaran
                let taOptions = '';
                history.forEach(h => {
                    const isActive = (h.status && h.status.toLowerCase() === 'aktif');
                    const isSelected = isActive ? 'selected' : '';
                    const activeLabel = isActive ? ' - (Aktif)' : '';
                    taOptions += `<option value="${h.id}" ${isSelected}>${h.tahun} (${h.semester})${activeLabel}</option>`;
                });

                // Deteksi Dark Mode (Dinamis)
                const isDarkMode = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark-mode');
                const swalBg = isDarkMode ? '#1e293b' : '#ffffff';
                const swalText = isDarkMode ? '#f8fafc' : '#1e293b';
                const swalLabel = isDarkMode ? '#94a3b8' : '#94a3b8';
                const inputBg = isDarkMode ? '#334155' : '#f8fafc';
                const inputText = isDarkMode ? '#f8fafc' : '#334155';
                const inputBorder = isDarkMode ? '#475569' : '#e2e8f0';

                // 2. Tampilkan Modal Pilihan (Premium Design - Dark Mode Support)
                Swal.fire({
                    background: swalBg,
                    color: swalText,
                    width: '380px',
                    title: '', // Kosongkan judul agar tidak ada jarak bawaan
                    html: `
                        <div class="flex flex-col items-center pt-2">
                            <!-- Header Section -->
                            <div class="w-12 h-12 rounded-full ${isDarkMode ? 'bg-blue-500/10 border-blue-500/20' : 'bg-blue-50 border-blue-100'} flex items-center justify-center text-blue-500 shadow-sm border mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <span class="text-xl font-black ${isDarkMode ? 'text-slate-50' : 'text-slate-800'} tracking-tight mb-1">Unduh E-Rapor Digital</span>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest italic mb-6">Silakan pilih periode laporan ananda</p>

                            <!-- Inputs Section -->
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
                    confirmButtonText: 'Mulai Unduh PDF',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: WARNA_PRIMARY,
                    cancelButtonColor: isDarkMode ? '#334155' : '#f1f5f9',
                    customClass: {
                        popup: 'rounded-3xl border-none shadow-2xl',
                        confirmButton: 'rounded-2xl px-8 py-4 font-bold uppercase tracking-widest text-[11px] shadow-lg shadow-blue-500/20',
                        cancelButton: `rounded-2xl px-8 py-4 font-bold uppercase tracking-widest text-[11px] ${isDarkMode ? '!text-slate-300' : '!text-slate-400'}`
                    },
                    reverseButtons: true,
                    preConfirm: () => {
                        const taId = document.getElementById('swal-ta').value;
                        const tipe = document.getElementById('swal-tipe').value;
                        const selectedTA = history.find(h => h.id == taId);
                        
                        return { 
                            ta: taId, 
                            tipe: tipe, 
                            semester: selectedTA ? selectedTA.semester : 'Ganjil' 
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { ta, tipe, semester } = result.value;
                        
                        // 3. Jalankan Proses Cek & Download
                        Swal.fire({
                            background: swalBg,
                            color: swalText,
                            title: `
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                                    <span class="text-lg font-bold ${isDarkMode ? 'text-slate-100' : 'text-slate-800'}">Mempersiapkan Rapor...</span>
                                </div>
                            `,
                            html: `<p class="text-xs ${isDarkMode ? 'text-slate-400' : 'text-slate-400'} font-medium">Sistem sedang merangkum hasil belajar ananda ke dalam PDF.</p>`,
                            allowOutsideClick: false,
                            showConfirmButton: false
                        });

                        const checkUrl = `${BASE_URL}/orangtua/rapor/cek-ketersediaan?semester=${semester}&ta=${ta}&tipe=${tipe}`;
                        const downloadUrl = `${BASE_URL}/orangtua/rapor/download?ta=${ta}&tipe=${tipe}&semester=${semester}`;

                        fetch(checkUrl)
                        .then(res => res.json())
                        .then(data => {
                            if(data.status === 'success') {
                                setTimeout(() => {
                                    Swal.close();
                                    window.location.href = downloadUrl;
                                }, 800);
                            } else {
                                Swal.fire({
                                    background: swalBg,
                                    color: swalText,
                                    icon: 'info',
                                    title: `<span class="${isDarkMode ? 'text-slate-100' : 'text-slate-800'}">Rapor Belum Terbit</span>`,
                                    html: `<p class="text-sm text-slate-500 font-medium">${data.message}</p>`,
                                    confirmButtonText: 'Siap, Mengerti',
                                    confirmButtonColor: WARNA_PRIMARY,
                                    customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-2xl px-8 py-3' }
                                });
                            }
                        })
                        .catch(() => {
                            Swal.close();
                            window.location.href = downloadUrl;
                        });
                    }
                });
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Gagal memuat riwayat akademik.', 'error');
            });
        });
    }

    // Efek Loading Kecil di Tombol WhatsApp
    const btnWa = document.querySelector('a[href^="https://wa.me"]');
    if (btnWa) {
        btnWa.addEventListener('click', function(e) {
            const originalHtml = this.innerHTML;
            this.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span>Membuka WhatsApp...</span>
            `;
            this.classList.add('opacity-80', 'pointer-events-none');

            // Reset tombol setelah 2 detik
            setTimeout(() => {
                this.innerHTML = originalHtml;
                this.classList.remove('opacity-80', 'pointer-events-none');
            }, 2000);
        });
    }
});