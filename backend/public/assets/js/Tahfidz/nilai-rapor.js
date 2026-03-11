setInterval(() => {
        const now = new Date();
        const el = document.getElementById('realtimeClock');
        if(el) el.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second:'2-digit' }) + ' WIB';
    }, 1000);

    const getInitials = (name) => {
        let initials = name.match(/\b\w/g) || [];
        return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
    };

    let totalSantri = 0;

    async function loadSiswa() {
        const kelas_id = document.getElementById('kelasSelect').value;
        const semester = document.getElementById('semesterSelect').value;

        if(!kelas_id) { 
            Swal.fire({
                icon: 'info',
                title: LANG.alert_title_hi,
                text: LANG.alert_desc_hi,
                buttonsStyling: false,
                customClass: { 
                    popup: 'rounded-3xl', 
                    confirmButton: 'px-6 py-2.5 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-900 transition-colors shadow-lg' 
                }
            });
            return; 
        }
        
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        
        const selectElement = document.getElementById('kelasSelect');
        const namaKelas = selectElement.options[selectElement.selectedIndex].text;
        document.getElementById('infoKelas').textContent = `${namaKelas} (${semester})`;

        const tbody = document.getElementById('tbodyNilai');
        tbody.innerHTML = `<tr><td colspan="4" class="text-center p-20"><div class="w-12 h-12 border-4 border-slate-200 rounded-full animate-spin mx-auto mb-4" style="border-top-color: var(--warna-utama);"></div><p class="text-slate-500 font-bold tracking-wide">${LANG.loading_sheet}</p></td></tr>`;

        try {
            const response = await fetch(`${LANG.fetch_url}?rombel_id=${kelas_id}&semester=${semester}`);
            const result = await response.json();

            if (result.status === 'success') {
                totalSantri = result.data.length;
                let html = '';

                if(totalSantri === 0) {
                    html = `<tr><td colspan="4" class="text-center p-12 text-rose-500 font-bold">${LANG.no_student}</td></tr>`;
                    updateProgress();
                } else {
                    result.data.forEach((siswa, index) => {
                        let capaianTeks = siswa.surah_terakhir ? `<span class="text-slate-700 font-bold">${siswa.surah_terakhir}</span> (${siswa.ayat_terakhir})` : `<span class="text-rose-500 font-semibold">${LANG.txt_not_deposited}</span>`;
                        let aktifTeks = siswa.total_setor > 0 ? `<span class="bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded text-[10px] font-bold uppercase ml-1">${siswa.total_setor}${LANG.txt_times_deposit}</span>` : '';

                        html += `
                        <tr class="group transition-all duration-200 hover:bg-slate-50/70 border-b border-slate-50 last:border-0">
                            <td class="px-5 py-4 text-center text-sm font-medium text-slate-300 group-hover:text-slate-500 border-r border-slate-50/50">${index + 1}</td>
                            <td class="px-5 py-4 border-r border-slate-50/50">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-sm ring-1 ring-slate-200/60 shadow-sm flex-shrink-0 group-hover:bg-white transition-all">
                                        ${getInitials(siswa.nama_lengkap)}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm group-hover:text-dinamis transition-colors tracking-tight">${siswa.nama_lengkap}</p>
                                        <div class="text-[11px] mt-1 text-slate-500">
                                            ${LANG.js_achievement} ${capaianTeks} ${aktifTeks}
                                        </div>
                                        <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                                        <input type="hidden" class="data-surah" value="${siswa.surah_terakhir}">
                                        <input type="hidden" class="data-total" value="${siswa.total_setor}">
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 border-r border-slate-50/50">
                                <select name="predikat[]" onchange="updateColor(this)" class="w-full text-sm font-bold border-0 ring-1 ring-slate-200 rounded-xl focus:ring-2 focus-ring-dinamis py-3 text-center transition-all cursor-pointer shadow-sm appearance-none">
                                    <option value="Sangat Baik" ${siswa.predikat === 'Sangat Baik' ? 'selected' : ''}>${LANG.pred_a}</option>
                                    <option value="Baik" ${siswa.predikat === 'Baik' ? 'selected' : ''}>${LANG.pred_b}</option>
                                    <option value="Cukup" ${siswa.predikat === 'Cukup' ? 'selected' : ''}>${LANG.pred_c}</option>
                                    <option value="Kurang" ${siswa.predikat === 'Kurang' ? 'selected' : ''}>${LANG.pred_d}</option>
                                </select>
                            </td>
                            <td class="px-5 py-4">
                                <textarea name="deskripsi[]" onkeyup="updateProgress()" rows="2" placeholder="${LANG.ph_narration}" class="w-full text-sm font-medium text-slate-700 border border-slate-200 rounded-xl focus:ring-2 focus-ring-dinamis bg-white transition-shadow placeholder-slate-400 px-4 py-3 shadow-inner resize-y leading-relaxed">${siswa.deskripsi || ''}</textarea>
                            </td>
                        </tr>
                        `;
                    });
                }
                tbody.innerHTML = html;
                document.querySelectorAll('select[name="predikat[]"]').forEach(sel => updateColor(sel));
                updateProgress(); 
            }
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center p-6 text-rose-500 font-bold">${LANG.err_fetch}</td></tr>`;
        }
    }

    function updateColor(selectElement) {
        const val = selectElement.value;
        selectElement.className = "w-full text-sm font-bold border-0 rounded-xl focus:ring-2 py-3 text-center transition-all cursor-pointer shadow-sm ring-1 appearance-none focus-ring-dinamis ";
        
        if(val.includes('Sangat Baik')) selectElement.className += "bg-emerald-50 text-emerald-700 ring-emerald-200 hover:bg-emerald-100";
        else if(val.includes('Baik')) selectElement.className += "bg-blue-50 text-blue-700 ring-blue-200 hover:bg-blue-100";
        else if(val.includes('Cukup')) selectElement.className += "bg-amber-50 text-amber-700 ring-amber-200 hover:bg-amber-100";
        else if(val.includes('Kurang')) selectElement.className += "bg-rose-50 text-rose-700 ring-rose-200 hover:bg-rose-100";
    }

    function autoFillDeskripsi() {
        if(totalSantri === 0) return;

        const predikats = document.querySelectorAll('select[name="predikat[]"]');
        const deskripsis = document.querySelectorAll('textarea[name="deskripsi[]"]');
        const surahs = document.querySelectorAll('.data-surah'); 
        const totals = document.querySelectorAll('.data-total');
        
        let filledCount = 0;

        deskripsis.forEach((textarea, i) => {
            if (textarea.value.trim() === '') {
                const p = predikats[i].value;
                const surahAnak = surahs[i].value;
                const totalSetor = parseInt(totals[i].value);
                
                let kalimatCapaian = '';
                if (surahAnak !== '' && surahAnak !== '-' && surahAnak !== 'null') {
                    kalimatCapaian = `${LANG.af_achievement} ${surahAnak}.`;
                }

                let kalimatAktif = '';
                if (totalSetor > 15) kalimatAktif = `${LANG.af_active} ${totalSetor}${LANG.af_active_end}`;
                else if (totalSetor === 0 || isNaN(totalSetor)) kalimatAktif = LANG.af_inactive;

                if (p.includes('Sangat Baik')) {
                    textarea.value = `${LANG.af_a_text}${kalimatCapaian}${kalimatAktif}${LANG.af_a_end}`;
                } else if (p.includes('Baik')) {
                    textarea.value = `${LANG.af_b_text}${kalimatCapaian}${LANG.af_b_end}`;
                } else if (p.includes('Cukup')) {
                    textarea.value = `${LANG.af_c_text}${kalimatCapaian}${LANG.af_c_end}`;
                } else if (p.includes('Kurang')) {
                    textarea.value = `${LANG.af_d_text}${kalimatAktif}${LANG.af_d_end}`;
                }
                
                filledCount++;
                
                textarea.style.backgroundColor = 'var(--warna-transparan)';
                textarea.style.borderColor = 'var(--warna-utama)';
                setTimeout(() => {
                    textarea.style.backgroundColor = '';
                    textarea.style.borderColor = '';
                }, 1500);
            }
        });

        if(filledCount > 0) {
            updateProgress(); 
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `${LANG.toast_af_title} ${filledCount} ${LANG.toast_af_desc}`,
                showConfirmButton: false,
                timer: 3000,
                customClass: { popup: 'rounded-2xl shadow-xl' }
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: LANG.af_full_title,
                text: LANG.af_full_desc,
                buttonsStyling: false,
                customClass: { popup: 'rounded-3xl', confirmButton: 'px-6 py-2 rounded-xl text-white bg-slate-800 font-bold hover:bg-slate-900' }
            });
        }
    }

    function updateProgress() {
        if(totalSantri === 0) return;
        const textareas = document.querySelectorAll('textarea[name="deskripsi[]"]');
        let terisi = 0;
        
        textareas.forEach(t => {
            if(t.value.trim() !== '') terisi++;
        });

        const percent = Math.round((terisi / totalSantri) * 100);
        
        document.getElementById('progressText').innerText = `${percent}% (${terisi}/${totalSantri})`;
        const bar = document.getElementById('progressBar');
        bar.style.width = `${percent}%`;

        const statusCetak = document.getElementById('statusCetak');

        if(percent === 100) {
            bar.classList.remove('bg-dinamis');
            bar.classList.add('bg-emerald-500', 'shadow-[0_0_10px_rgba(16,185,129,0.5)]');
            if(statusCetak) statusCetak.innerHTML = `<span class="text-emerald-600">${LANG.status_done}</span>`;
        } else {
            bar.classList.add('bg-dinamis');
            bar.classList.remove('bg-emerald-500', 'shadow-[0_0_10px_rgba(16,185,129,0.5)]');
            if(statusCetak) statusCetak.innerHTML = `<span class="text-amber-500 animate-pulse">${LANG.status_not_yet}</span>`;
        }
    }

    async function simpanNilai() {
        const form = document.getElementById('formNilaiRapor');
        const formData = new FormData(form);
        formData.append('semester', document.getElementById('semesterSelect').value);

        const btn = document.querySelector('button[onclick="simpanNilai()"]');
        const originalText = btn.innerHTML;

        try {
            btn.innerHTML = `<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div> ${LANG.saving}`;
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');

            Swal.fire({
                title: LANG.saving_title,
                html: LANG.saving_desc,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: { popup: 'rounded-3xl' },
                didOpen: () => { Swal.showLoading(); }
            });

            const response = await fetch(LANG.save_url, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            
            btn.innerHTML = originalText;
            btn.disabled = false;
            btn.classList.remove('opacity-80', 'cursor-not-allowed');

            if (result.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: LANG.success_title,
                    text: result.message || LANG.success_default,
                    buttonsStyling: false,
                    customClass: { popup: 'rounded-3xl', confirmButton: 'px-8 py-3 rounded-xl font-bold text-white bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/30' }
                });
            } else if (result.status === 'warning') {
                Swal.fire({
                    icon: 'warning',
                    title: LANG.warning_title,
                    text: result.message,
                    buttonsStyling: false,
                    customClass: { popup: 'rounded-3xl', confirmButton: 'px-8 py-3 rounded-xl font-bold text-white bg-amber-500 hover:bg-amber-600 shadow-lg' }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: LANG.error_title,
                    text: result.message,
                    buttonsStyling: false,
                    customClass: { popup: 'rounded-3xl', confirmButton: 'px-8 py-3 rounded-xl font-bold text-white bg-rose-500 hover:bg-rose-600 shadow-lg' }
                });
            }
        } catch (error) {
            btn.innerHTML = originalText;
            btn.disabled = false;
            btn.classList.remove('opacity-80', 'cursor-not-allowed');
            
            Swal.fire({
                icon: 'error',
                title: LANG.server_error,
                text: LANG.server_error_desc,
                buttonsStyling: false,
                customClass: { popup: 'rounded-3xl', confirmButton: 'px-8 py-3 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-900 shadow-lg' }
            });
        }
    }