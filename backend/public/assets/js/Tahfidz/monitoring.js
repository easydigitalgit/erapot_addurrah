let currentPredikat = 'semua';

    function setPredikatFilter(kategori, btnElem) {
        currentPredikat = kategori;

        document.querySelectorAll('.btn-predikat').forEach(btn => {
            btn.classList.remove('bg-primary', 'text-white', 'ring-primary', 'ring-2', 'ring-offset-2');
            btn.classList.add('bg-white', 'text-slate-600', 'ring-1', 'ring-slate-200');
        });
        btnElem.classList.remove('bg-white', 'text-slate-600', 'ring-1', 'ring-slate-200');
        btnElem.classList.add('bg-primary', 'text-white', 'ring-primary', 'ring-2', 'ring-offset-2');

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
                matchPredikat = (predikat === 'Kurang Lancar' || predikat === 'Belum Hafal');
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
        if(!kelas_id) { alert(LANG.alert_select); return; }

        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        
        document.getElementById('searchInput').value = ''; 
        document.getElementById('filterKeaktifan').value = 'semua';
        document.querySelector('.btn-predikat').click(); 

        const tbody = document.getElementById('tbodyMonitoring');
        tbody.innerHTML = `<tr><td colspan="6" class="text-center p-16 text-slate-400 font-medium"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto mb-3"></div>${LANG.loading_data}</td></tr>`;

        try {
            const response = await fetch(`${LANG.fetch_url}?rombel_id=${kelas_id}`);
            const result = await response.json();

            if (result.status === 'success') {
                let totalSiswa = result.data.length;
                let totalSetoran = 0;
                let santriAktif = 0;

                const getInitials = (name) => {
                    let initials = name.match(/\b\w/g) || [];
                    return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
                };

                let html = '';
                if(totalSiswa === 0) {
                    html = `<tr><td colspan="6" class="text-center p-12 text-rose-500 font-medium">${LANG.no_student}</td></tr>`;
                } else {
                    result.data.forEach((siswa, index) => {
                        totalSetoran += parseInt(siswa.total_setor);
                        if(siswa.total_setor > 0) santriAktif++;

                        let badgeColor = 'bg-slate-50 text-slate-500 ring-slate-200';
                        if (siswa.predikat_terakhir === 'Sangat Lancar') badgeColor = 'bg-emerald-50 text-emerald-700 ring-emerald-200/50';
                        if (siswa.predikat_terakhir === 'Lancar') badgeColor = 'bg-blue-50 text-blue-700 ring-blue-200/50';
                        if (siswa.predikat_terakhir === 'Kurang Lancar') badgeColor = 'bg-amber-50 text-amber-700 ring-amber-200/50';
                        if (siswa.predikat_terakhir === 'Belum Hafal') badgeColor = 'bg-rose-50 text-rose-700 ring-rose-200/50';

                        let dotsHTML = '';
                        let trenIkon = '<svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>';
                        
                        if(siswa.riwayat_5 && siswa.riwayat_5.length > 0) {
                            let riwayatReversed = [...siswa.riwayat_5].reverse();
                            if(siswa.riwayat_5.length >= 2) {
                                let curr = siswa.riwayat_5[0]; let prev = siswa.riwayat_5[1]; 
                                const skor = {"Sangat Lancar": 4, "Lancar": 3, "Kurang Lancar": 2, "Belum Hafal": 1};
                                if(skor[curr] > skor[prev]) trenIkon = '<svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>';
                                else if(skor[curr] < skor[prev]) trenIkon = '<svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>';
                            }
                            riwayatReversed.forEach(p => {
                                let dColor = 'bg-slate-200';
                                if(p === 'Sangat Lancar') dColor = 'bg-emerald-400';
                                else if(p === 'Lancar') dColor = 'bg-blue-400';
                                else if(p === 'Kurang Lancar') dColor = 'bg-amber-400';
                                else if(p === 'Belum Hafal') dColor = 'bg-rose-400';
                                dotsHTML += `<div class="w-1.5 h-1.5 rounded-full ${dColor}" title="${p}"></div>`;
                            });
                            for(let i=siswa.riwayat_5.length; i<5; i++) dotsHTML += `<div class="w-1.5 h-1.5 rounded-full bg-slate-100"></div>`;
                        } else {
                            dotsHTML = '<div class="w-1.5 h-1.5 rounded-full bg-slate-100"></div>'.repeat(5);
                        }

                        let progresTarget = siswa.total_setor > 0 ? Math.min(Math.round((siswa.total_setor / 30) * 100), 100) : 0; 
                        let warnaProgres = progresTarget >= 80 ? 'bg-emerald-500' : (progresTarget >= 50 ? `bg-primary` : 'bg-amber-500');
                        let lastActive = siswa.total_setor > 0 ? (index % 3 === 0 ? LANG.dep_today : (index % 2 === 0 ? LANG.dep_yesterday : LANG.dep_3_days)) : LANG.dep_never;
                        
                        let dataAktif = siswa.total_setor > 0 ? 'aktif' : 'pasif';
                        let dataNama = siswa.nama_lengkap.toLowerCase();

                        html += `
                        <tr class="baris-santri group transition-all duration-200 hover:bg-slate-50/70 border-b border-slate-50 last:border-0 cursor-pointer" 
                            data-predikat="${siswa.predikat_terakhir}" 
                            data-aktif="${dataAktif}" 
                            data-nama="${dataNama}" 
                            onclick="bukaModalRiwayat(${siswa.id})">
                            
                            <td class="px-5 py-4 text-center text-sm font-medium text-slate-300 group-hover:text-slate-500 border-r border-slate-50/50">${index + 1}</td>
                            
                            <td class="px-5 py-4 border-r border-slate-50/50">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs ring-1 ring-slate-200/60 flex-shrink-0 group-hover:bg-white group-hover:shadow-sm transition-all">
                                        ${getInitials(siswa.nama_lengkap)}
                                    </div>
                                    <div>
                                        <p class="nama-santri font-bold text-slate-800 text-sm group-hover:text-primary transition-colors flex items-center gap-2 tracking-tight">
                                            ${siswa.nama_lengkap}
                                            <svg class="w-3.5 h-3.5 text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[10px] font-bold text-slate-400 tracking-wider">${LANG.nis_label} ${siswa.nis || '-'}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 border-r border-slate-50/50">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">${LANG.target_juz}</span>
                                    <span class="text-xs font-bold text-slate-700">${progresTarget}%</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full ${warnaProgres} transition-all duration-1000" style="width: ${progresTarget}%"></div>
                                </div>
                            </td>

                            <td class="px-5 py-4 border-r border-slate-50/50">
                                <div class="flex items-center justify-center gap-4">
                                    <div class="text-center">
                                        <p class="text-sm font-black text-slate-800">${siswa.total_setor} <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">x</span></p>
                                    </div>
                                    <div class="flex gap-1 items-center bg-white px-2 py-1.5 rounded-lg border border-slate-100 shadow-sm">
                                        ${dotsHTML}
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 border-r border-slate-50/50">
                                ${siswa.surah_terakhir !== '-' ? `
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <div class="font-bold text-slate-800 text-sm tracking-tight truncate max-w-[140px]" title="${siswa.surah_terakhir}">${siswa.surah_terakhir}</div>
                                        <div class="text-[10px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200/60 whitespace-nowrap">${LANG.ayat_lbl} ${siswa.ayat_terakhir}</div>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-1.5 text-[10px] font-medium text-slate-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Setor: <span class="font-bold text-slate-500">${lastActive}</span>
                                    </div>
                                ` : `<span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">${LANG.not_deposited}</span>`}
                            </td>

                            <td class="px-5 py-4 text-center">
                                ${siswa.predikat_terakhir !== '-' ? `
                                    <div class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-xl ring-1 ${badgeColor} shadow-sm bg-white">
                                        ${trenIkon}
                                        <span class="text-[10px] font-bold uppercase tracking-wider">${siswa.predikat_terakhir}</span>
                                    </div>
                                ` : '<span class="text-slate-300">-</span>'}
                            </td>
                        </tr>
                        `;
                    });

                    document.getElementById('statTotalSantri').innerText = totalSiswa;
                    document.getElementById('statTotalSetoran').innerHTML = `${totalSetoran}<span class="text-lg text-slate-400 font-bold ml-1">x</span>`;
                    document.getElementById('statKeaktifan').innerHTML = `${Math.round((santriAktif / totalSiswa) * 100)}<span class="text-lg text-slate-400 font-bold ml-1">%</span>`;
                }
                tbody.innerHTML = html;
            }
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center p-6 text-rose-500">${LANG.err_monitor}</td></tr>`;
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
        document.getElementById('modalNamaSantri').innerText = LANG.modal_loading;
        document.getElementById('modalAvatar').innerHTML = `<svg class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

        try {
            const response = await fetch(`${LANG.riwayat_url}?siswa_id=${siswa_id}`);
            const result = await response.json();
            
            loadingModal.classList.add('hidden');

            if (result.status === 'success') {
                document.getElementById('modalNamaSantri').innerText = result.siswa;
                
                let initials = result.siswa.match(/\b\w/g) || [];
                let inisialText = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
                document.getElementById('modalAvatar').innerHTML = inisialText;
                
                if(result.data.length === 0) {
                    timelineBox.innerHTML = `
                        <div class="p-8 text-center bg-white rounded-3xl border border-slate-100 shadow-sm ml-6">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-700">${LANG.modal_no_hist}</h4>
                            <p class="text-xs text-slate-500 mt-1">${LANG.modal_no_desc}</p>
                        </div>
                    `;
                    return;
                }

                let timelineHTML = '';
                result.data.forEach((item, index) => {
                    let dotColor = item.jenis_setoran === 'Ziyadah' ? 'bg-emerald-400 ring-emerald-100' : 'bg-blue-400 ring-blue-100';
                    let badgeColor = 'bg-slate-50 text-slate-600 border-slate-200';
                    let iconStatus = '';
                    
                    if (item.predikat === 'Sangat Lancar') { badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-200'; iconStatus = '🌟'; }
                    else if (item.predikat === 'Lancar') { badgeColor = 'bg-blue-50 text-blue-700 border-blue-200'; iconStatus = '✨'; }
                    else if (item.predikat === 'Kurang Lancar') { badgeColor = 'bg-amber-50 text-amber-700 border-amber-200'; iconStatus = '⚠️'; }
                    else if (item.predikat === 'Belum Hafal') { badgeColor = 'bg-rose-50 text-rose-700 border-rose-200'; iconStatus = '🚨'; }

                    let dateObj = new Date(item.tanggal);
                    let tglNum = dateObj.getDate();
                    let monthStr = dateObj.toLocaleDateString('id-ID', { month: 'short' });
                    let yearStr = dateObj.getFullYear();

                    timelineHTML += `
                    <div class="relative pl-10 py-2 group">
                        <div class="absolute w-4 h-4 rounded-full ${dotColor} ring-[6px] -left-[9px] top-7 shadow-sm group-hover:scale-125 transition-transform duration-300"></div>
                        
                        <div class="bg-white p-5 rounded-3xl border border-slate-100/80 shadow-sm hover:shadow-lg hover:border-primary/30 transition-all duration-300">
                            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start sm:items-center">
                                <div class="flex flex-col items-center justify-center bg-slate-50 rounded-xl px-4 py-2 border border-slate-100 min-w-[4rem] flex-shrink-0">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">${monthStr}</span>
                                    <span class="text-xl font-black text-slate-700 leading-none my-0.5">${tglNum}</span>
                                    <span class="text-[9px] font-bold text-slate-400">${yearStr}</span>
                                </div>
                                <div class="flex-1 w-full">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">${item.jenis_setoran}</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-lg border shadow-sm ${badgeColor}">${iconStatus} ${item.predikat}</span>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800 tracking-tight mt-1">${LANG.surah_lbl} ${item.surah} <span class="text-sm font-semibold text-slate-500 ml-1">${LANG.ayat_lbl} ${item.ayat}</span></h4>
                                </div>
                            </div>
                            ${item.catatan ? `
                                <div class="mt-4 ml-0 sm:ml-20 relative">
                                    <div class="absolute -top-2 left-6 w-4 h-4 bg-amber-50 rotate-45 border-l border-t border-amber-200"></div>
                                    <div class="bg-amber-50 p-3.5 rounded-2xl rounded-tl-none border border-amber-200 relative z-10">
                                        <p class="text-xs text-amber-900 font-medium leading-relaxed italic flex gap-2">
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
                <div class="p-6 text-center bg-rose-50 rounded-3xl border border-rose-100 ml-6">
                    <p class="text-rose-500 text-sm font-bold">${LANG.err_history}</p>
                </div>
            `;
        }
    }