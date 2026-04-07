/**
 * File: public/assets/js/Tahfidz/monitoring.js
 */

let currentPredikat = 'semua';

function setPredikatFilter(kategori, btnElem) {
    currentPredikat = kategori;

    document.querySelectorAll('.btn-predikat').forEach(btn => {
        btn.classList.remove('bg-primary', 'text-white', 'ring-primary', 'ring-2', 'ring-offset-2', 'dark:ring-offset-slate-800');
        btn.classList.add('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300', 'ring-1', 'ring-slate-200', 'dark:ring-slate-600');
    });
    
    btnElem.classList.remove('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300', 'ring-1', 'ring-slate-200', 'dark:ring-slate-600');
    btnElem.classList.add('bg-primary', 'text-white', 'ring-primary', 'ring-2', 'ring-offset-2', 'dark:ring-offset-slate-800');

    applyMultiFilter();
}

function applyMultiFilter() {
    let searchVal = document.getElementById("searchInput").value.toLowerCase();
    let statusVal = document.getElementById("filterKeaktifan").value; 
    let baris = document.querySelectorAll("#tbodyMonitoring .baris-santri");

    baris.forEach(row => {
        let nama = row.dataset.nama;
        let predikat = row.dataset.predikat;
        let keaktifan = row.dataset.aktif;

        let matchSearch = nama.includes(searchVal);
        let matchStatus = (statusVal === 'semua') || (keaktifan === statusVal);

        let matchPredikat = true;
        if (currentPredikat === 'Kurang Lancar') {
            matchPredikat = (predikat === 'Kurang Lancar' || predikat === 'Jayyid' || predikat === 'Maqbül');
        } else if (currentPredikat === 'Sangat Lancar') {
            matchPredikat = (predikat === 'Sangat Lancar' || predikat === 'Mumtaz');
        } else if (currentPredikat === 'Belum Hafal') {
            matchPredikat = (predikat === 'Belum Hafal' || predikat === 'Mardüd' || predikat === '-');
        } else if (currentPredikat !== 'semua') {
            matchPredikat = (predikat === currentPredikat);
        }

        if (matchSearch && matchStatus && matchPredikat) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

async function loadMonitoring() {
    const kelas_id = document.getElementById('kelasSelect').value;
    const juzName = document.getElementById('juzSelect').value;

    if(!kelas_id || !juzName) { 
        return; 
    }

    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('tableContainer').style.display = 'block';
    
    document.getElementById('searchInput').value = ''; 
    document.getElementById('filterKeaktifan').value = 'semua';
    document.querySelector('.btn-predikat').click(); 

    const tbody = document.getElementById('tbodyMonitoring');
    tbody.innerHTML = `<tr><td colspan="7" class="text-center p-16 text-slate-400 font-medium"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto mb-3"></div>Mengambil data klasemen...</td></tr>`;

    try {
        const response = await fetch(`${CONFIG.fetch_url}?rombel_id=${kelas_id}&juz=${juzName}`);
        const result = await response.json();

        if (result.status === 'success') {
            let totalSiswa = result.data.length;
            let totalSetoran = 0;
            let santriAktif = 0;

            const getInitials = (name) => {
                let initials = name.match(/\b\w/g) || [];
                return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
            };

            let listSurahJuzIni = JUZ_DATA_DB[juzName] || [];
            let maxSetoranJuz = listSurahJuzIni.length; 
            if (maxSetoranJuz === 0) maxSetoranJuz = 30; 

            let html = '';
            if(totalSiswa === 0) {
                html = `<tr><td colspan="7" class="text-center p-12 text-rose-500 font-medium">Tidak ada santri pada kelas ini.</td></tr>`;
            } else {
                result.data.forEach((siswa, index) => {
                    totalSetoran += parseInt(siswa.total_setor);
                    if(siswa.total_setor > 0) santriAktif++;

                    let badgeColor = 'bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 ring-slate-200 dark:ring-slate-700';
                    if (siswa.predikat_terakhir === 'Sangat Lancar' || siswa.predikat_terakhir === 'Mumtaz') badgeColor = 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 ring-emerald-200/50 dark:ring-emerald-800/50';
                    if (siswa.predikat_terakhir === 'Lancar' || siswa.predikat_terakhir === 'Jayyid Jiddan') badgeColor = 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 ring-blue-200/50 dark:ring-blue-800/50';
                    if (siswa.predikat_terakhir === 'Kurang Lancar' || siswa.predikat_terakhir === 'Jayyid' || siswa.predikat_terakhir === 'Maqbül') badgeColor = 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 ring-amber-200/50 dark:ring-amber-800/50';
                    if (siswa.predikat_terakhir === 'Belum Hafal' || siswa.predikat_terakhir === 'Mardüd') badgeColor = 'bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 ring-rose-200/50 dark:ring-rose-800/50';

                    // --- LOGIKA HYBRID AVATAR (TABEL KLASEMEN) ---
                    let avatarHtml = '';
                    if (siswa.foto_fix && String(siswa.foto_fix).trim() !== '' && siswa.foto_fix !== 'null') {
                        const cleanBaseUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : '').replace(/\/$/, '');
                        const cacheBuster = '?v=' + new Date().getTime();
                        
                        const urlAvatars = `${cleanBaseUrl}/assets/uploads/avatars/${siswa.foto_fix}${cacheBuster}`;
                        const urlSiswa = `${cleanBaseUrl}/uploads/siswa/${siswa.foto_fix}${cacheBuster}`;
                        const fallbackInitial = getInitials(siswa.nama_lengkap);
                        
                        // Coba folder avatars -> gagal? coba folder siswa -> gagal? tampilkan inisial
                        avatarHtml = `<img src="${urlAvatars}" class="w-full h-full object-cover" onerror="this.onerror=function(){ this.outerHTML='${fallbackInitial}'; }; this.src='${urlSiswa}';">`;
                    } else {
                        avatarHtml = getInitials(siswa.nama_lengkap);
                    }

                    let dotsHTML = '';
                    let trenIkon = '<svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>';
                    
                    if(siswa.riwayat_5 && siswa.riwayat_5.length > 0) {
                        let riwayatReversed = [...siswa.riwayat_5].reverse();
                        if(siswa.riwayat_5.length >= 2) {
                            let curr = siswa.riwayat_5[0]; let prev = siswa.riwayat_5[1]; 
                            const skor = {"Sangat Lancar": 4, "Mumtaz": 4, "Lancar": 3, "Jayyid Jiddan": 3, "Kurang Lancar": 2, "Jayyid": 2, "Maqbül": 2, "Belum Hafal": 1, "Mardüd": 1};
                            if(skor[curr] > skor[prev]) trenIkon = '<svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>';
                            else if(skor[curr] < skor[prev]) trenIkon = '<svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>';
                        }
                        riwayatReversed.forEach(p => {
                            let dColor = 'bg-slate-200 dark:bg-slate-700';
                            if(p === 'Sangat Lancar' || p === 'Mumtaz') dColor = 'bg-emerald-400';
                            else if(p === 'Lancar' || p === 'Jayyid Jiddan') dColor = 'bg-blue-400';
                            else if(p === 'Kurang Lancar' || p === 'Jayyid' || p === 'Maqbül') dColor = 'bg-amber-400';
                            else if(p === 'Belum Hafal' || p === 'Mardüd') dColor = 'bg-rose-400';
                            dotsHTML += `<div class="w-1.5 h-1.5 rounded-full ${dColor}" title="${p}"></div>`;
                        });
                        for(let i=siswa.riwayat_5.length; i<5; i++) dotsHTML += `<div class="w-1.5 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800"></div>`;
                    } else {
                        dotsHTML = '<div class="w-1.5 h-1.5 rounded-full bg-slate-100 dark:bg-slate-800"></div>'.repeat(5);
                    }

                    let progresTarget = siswa.total_setor > 0 ? Math.min(Math.round((siswa.total_setor / maxSetoranJuz) * 100), 100) : 0; 
                    let warnaProgres = progresTarget >= 80 ? 'bg-emerald-500' : (progresTarget >= 50 ? `bg-primary` : 'bg-amber-500');
                    
                    let lastActive = siswa.total_setor > 0 ? "Pernah Setor" : "Belum Pernah";
                    let dataAktif = siswa.total_setor > 0 ? 'aktif' : 'pasif';
                    let dataNama = (siswa.nama_lengkap || '').toLowerCase();

                    html += `
                    <tr class="baris-santri group transition-all duration-200 hover:bg-slate-50/70 dark:hover:bg-slate-700/30 border-b border-slate-50 dark:border-slate-700/50 last:border-0 cursor-pointer" 
                        data-predikat="${siswa.predikat_terakhir}" 
                        data-aktif="${dataAktif}" 
                        data-nama="${dataNama}" 
                        onclick="bukaModalRiwayat(${siswa.id})">
                        
                        <td class="px-5 py-4 text-center text-sm font-medium text-slate-300 dark:text-slate-600 group-hover:text-slate-500 dark:group-hover:text-slate-400 border-r border-slate-50/50 dark:border-slate-700/50">${index + 1}</td>
                        
                        <td class="px-5 py-4 border-r border-slate-50/50 dark:border-slate-700/50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center font-bold text-xs ring-1 ring-slate-200/60 dark:ring-slate-600 flex-shrink-0 overflow-hidden group-hover:shadow-sm transition-all">
                                    ${avatarHtml}
                                </div>
                                <div>
                                    <p class="nama-santri font-bold text-slate-800 dark:text-white text-sm group-hover:text-primary transition-colors flex items-center gap-2 tracking-tight">
                                        ${siswa.nama_lengkap}
                                        <svg class="w-3.5 h-3.5 text-slate-300 dark:text-slate-600 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] font-bold text-slate-400 tracking-wider">NIS: ${siswa.nis || '-'}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 border-r border-slate-50/50 dark:border-slate-700/50 text-center">
                            <span class="text-xs font-bold text-primary bg-primary-light px-3 py-1.5 rounded-lg border border-primary/20">${siswa.target_juz}</span>
                        </td>

                        <td class="px-5 py-4 border-r border-slate-50/50 dark:border-slate-700/50">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Progress Juz</span>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">${progresTarget}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full ${warnaProgres} transition-all duration-1000" style="width: ${progresTarget}%"></div>
                            </div>
                        </td>

                        <td class="px-5 py-4 border-r border-slate-50/50 dark:border-slate-700/50">
                            <div class="flex items-center justify-center gap-4">
                                <div class="text-center">
                                    <p class="text-sm font-black text-slate-800 dark:text-white">${siswa.total_setor} <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">x</span></p>
                                </div>
                                <div class="flex gap-1 items-center bg-white dark:bg-slate-900 px-2 py-1.5 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm">
                                    ${dotsHTML}
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 border-r border-slate-50/50 dark:border-slate-700/50">
                            ${siswa.surah_terakhir !== '-' ? `
                                <div class="flex items-center gap-2 mb-0.5">
                                    <div class="font-bold text-slate-800 dark:text-white text-sm tracking-tight truncate max-w-[160px]" title="${siswa.surah_terakhir}">${siswa.surah_terakhir}</div>
                                </div>
                                <div class="flex items-center gap-1.5 mt-1.5 text-[10px] font-medium text-slate-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="font-bold text-slate-500 dark:text-slate-400">${lastActive}</span>
                                </div>
                            ` : `<span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-900 px-2 py-1 rounded-md border border-slate-100 dark:border-slate-800">Belum Setor</span>`}
                        </td>

                        <td class="px-5 py-4 text-center">
                            ${siswa.predikat_terakhir !== '-' ? `
                                <div class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-xl ring-1 ${badgeColor} shadow-sm bg-white dark:bg-slate-900">
                                    ${trenIkon}
                                    <span class="text-[10px] font-bold uppercase tracking-wider">${siswa.predikat_terakhir}</span>
                                </div>
                            ` : '<span class="text-slate-300 dark:text-slate-600">-</span>'}
                        </td>
                    </tr>
                    `;
                });

                document.getElementById('statTotalSantri').innerText = totalSiswa;
                document.getElementById('statTotalSetoran').innerHTML = `${totalSetoran}<span class="text-lg text-slate-400 font-bold ml-1">x</span>`;
                let prc = totalSiswa > 0 ? Math.round((santriAktif / totalSiswa) * 100) : 0;
                document.getElementById('statKeaktifan').innerHTML = `${prc}<span class="text-lg text-slate-400 font-bold ml-1">%</span>`;
            }
            tbody.innerHTML = html;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Ditolak',
                text: result.message,
                customClass: { popup: 'rounded-[2rem] dark:bg-slate-800 dark:text-white' }
            });
            tbody.innerHTML = `<tr><td colspan="7" class="text-center p-6 text-red-500 font-bold">${result.message}</td></tr>`;
        }
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center p-6 text-rose-500">Gagal menarik data dari server.</td></tr>`;
    }
}

const modal = document.getElementById('modalRiwayat');
const modalBackdrop = document.getElementById('modalBackdrop');
const modalPanel = document.getElementById('modalPanel');
const loadingModal = document.getElementById('loadingModal');
const timelineBox = document.getElementById('timelineContainer');

function tutupModal() {
    modalBackdrop.classList.remove('opacity-100');
    modalBackdrop.classList.add('opacity-0');
    modalPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    modalPanel.classList.add('opacity-0', 'translate-y-8', 'sm:scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto'; 
    }, 300);
}

async function bukaModalRiwayat(siswa_id) {
    const juzName = document.getElementById('juzSelect').value;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; 
    
    setTimeout(() => {
        modalBackdrop.classList.remove('opacity-0');
        modalBackdrop.classList.add('opacity-100');
        modalPanel.classList.remove('opacity-0', 'translate-y-8', 'sm:scale-95');
        modalPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 10);

    timelineBox.innerHTML = '';
    loadingModal.classList.remove('hidden');
    document.getElementById('modalNamaSantri').innerText = result.siswa;
            
            // --- LOGIKA HYBRID AVATAR (MODAL KLASEMEN) ---
            let avatarHtml = '';
            let initials = result.siswa.match(/\b\w/g) || [];
            let inisialText = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();

            if (result.foto && result.foto !== 'null' && String(result.foto).trim() !== '') {
                const cleanBaseUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : '').replace(/\/$/, '');
                const cacheBuster = '?v=' + new Date().getTime();
                
                const urlAvatars = `${cleanBaseUrl}/assets/uploads/avatars/${result.foto}${cacheBuster}`;
                const urlSiswa = `${cleanBaseUrl}/uploads/siswa/${result.foto}${cacheBuster}`;
                
                // Coba folder avatars -> gagal? coba folder siswa -> gagal? tampilkan inisial
                avatarHtml = `<img src="${urlAvatars}" class="w-full h-full object-cover" onerror="this.onerror=function(){ this.outerHTML='${inisialText}'; }; this.src='${urlSiswa}';">`;
            } else {
                avatarHtml = inisialText;
            }
            document.getElementById('modalAvatar').innerHTML = avatarHtml;
            // ------------------------------------------------

    try {
        const response = await fetch(`${CONFIG.riwayat_url}?siswa_id=${siswa_id}&juz=${juzName}`);
        const result = await response.json();
        
        loadingModal.classList.add('hidden');

        if (result.status === 'success') {
            document.getElementById('modalNamaSantri').innerText = result.siswa;
            
            let initials = result.siswa.match(/\b\w/g) || [];
            let inisialText = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
            document.getElementById('modalAvatar').innerHTML = inisialText;
            
            if(result.data.length === 0) {
                timelineBox.innerHTML = `
                    <div class="p-8 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm ml-6">
                        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-700 dark:text-white">Tidak Ada Riwayat</h4>
                        <p class="text-xs text-slate-500 mt-1">Santri belum menyetorkan hafalan di Juz ini.</p>
                    </div>
                `;
                return;
            }

            let timelineHTML = '';
            result.data.forEach((item, index) => {
                let dotColor = item.jenis_setoran === 'Ziyadah' ? 'bg-emerald-400 ring-emerald-100 dark:ring-emerald-900/50' : 'bg-blue-400 ring-blue-100 dark:ring-blue-900/50';
                let badgeColor = 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-900 dark:text-slate-300 dark:border-slate-700';
                let iconStatus = '';
                
                if (item.predikat === 'Sangat Lancar' || item.predikat === 'Mumtaz') { badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800'; iconStatus = '🌟'; }
                else if (item.predikat === 'Lancar' || item.predikat === 'Jayyid Jiddan') { badgeColor = 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800'; iconStatus = '✨'; }
                else if (item.predikat === 'Kurang Lancar' || item.predikat === 'Jayyid' || item.predikat === 'Maqbul') { badgeColor = 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800'; iconStatus = '⚠️'; }
                else if (item.predikat === 'Belum Hafal' || item.predikat === 'Mardüd') { badgeColor = 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800'; iconStatus = '🚨'; }

                let dateObj = new Date(item.tanggal);
                let tglNum = dateObj.getDate();
                let monthStr = dateObj.toLocaleDateString('id-ID', { month: 'short' });
                let yearStr = dateObj.getFullYear();

                let fullSurah = item.surah;
                
                timelineHTML += `
                <div class="relative pl-10 py-2 group">
                    <div class="absolute w-4 h-4 rounded-full ${dotColor} ring-[6px] -left-[9px] top-7 shadow-sm group-hover:scale-125 transition-transform duration-300"></div>
                    
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-100/80 dark:border-slate-800 shadow-sm hover:shadow-lg hover:border-primary/30 transition-all duration-300">
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start sm:items-center">
                            <div class="flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-800 rounded-xl px-4 py-2 border border-slate-100 dark:border-slate-700 min-w-[4rem] flex-shrink-0">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">${monthStr}</span>
                                <span class="text-xl font-black text-slate-700 dark:text-white leading-none my-0.5">${tglNum}</span>
                                <span class="text-[9px] font-bold text-slate-400">${yearStr}</span>
                            </div>
                            <div class="flex-1 w-full">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">${item.jenis_setoran}</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-lg border shadow-sm ${badgeColor}">${iconStatus} ${item.predikat}</span>
                                </div>
                                <h4 class="text-lg font-bold text-slate-800 dark:text-white tracking-tight mt-1">Surah ${fullSurah}</h4>
                            </div>
                        </div>
                        ${item.catatan ? `
                            <div class="mt-4 ml-0 sm:ml-20 relative">
                                <div class="absolute -top-2 left-6 w-4 h-4 bg-amber-50 dark:bg-amber-900/30 rotate-45 border-l border-t border-amber-200 dark:border-amber-800"></div>
                                <div class="bg-amber-50 dark:bg-amber-900/20 p-3.5 rounded-2xl rounded-tl-none border border-amber-200 dark:border-amber-800/50 relative z-10">
                                    <p class="text-xs text-amber-900 dark:text-amber-400 font-medium leading-relaxed italic flex gap-2">
                                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 18L14.017 21 16.133 21 18.89 15 21.646 15 21.646 9 14.017 9zM4.017 18L4.017 21 6.133 21 8.89 15 11.646 15 11.646 9 4.017 9z"></path></svg>
                                        ${item.catatan}
                                    </p>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
                `;
            });
            timelineBox.innerHTML = timelineHTML;
        }
    } catch (error) {
        loadingModal.classList.add('hidden');
        timelineBox.innerHTML = `
            <div class="p-6 text-center bg-rose-50 dark:bg-rose-900/30 rounded-3xl border border-rose-100 dark:border-rose-900 ml-6">
                <p class="text-rose-500 font-bold">Gagal mengambil riwayat dari server.</p>
            </div>
        `;
    }
}