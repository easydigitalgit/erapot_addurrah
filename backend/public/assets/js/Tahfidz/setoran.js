const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: { popup: 'rounded-2xl shadow-xl border border-slate-100' },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    setInterval(() => {
        const now = new Date();
        const el = document.getElementById('realtimeClock');
        if(el) el.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second:'2-digit' }) + ' WIB';
    }, 1000);

    document.addEventListener('keydown', function(event) {
        if (event.altKey && (event.key === 's' || event.key === 'S')) {
            event.preventDefault();
            if(document.getElementById('tableContainer').style.display === 'block') {
                simpanSetoran();
            }
        }
    });

    let isFocusMode = false;
    function toggleFocusMode() {
        isFocusMode = !isFocusMode;
        const topSec = document.getElementById('topSection');
        const legend = document.getElementById('legendPenilaian');
        const btnFocus = document.getElementById('btnFocus');
        const textBtnFocus = document.getElementById('textBtnFocus');
        const scrollWrap = document.getElementById('scrollTableWrapper');
        
        if (isFocusMode) {
            topSec.style.display = 'none';
            legend.style.display = 'none';
            scrollWrap.classList.replace('max-h-[55vh]', 'max-h-[80vh]');
            textBtnFocus.innerText = LANG.btn_exit_focus;
            btnFocus.classList.replace('bg-slate-800', 'bg-rose-500');
            btnFocus.classList.replace('hover:bg-slate-900', 'hover:bg-rose-600');
            document.documentElement.requestFullscreen().catch(err => console.log(err));
        } else {
            topSec.style.display = 'block';
            legend.style.display = 'block';
            scrollWrap.classList.replace('max-h-[80vh]', 'max-h-[55vh]');
            textBtnFocus.innerText = LANG.btn_enter_focus;
            btnFocus.classList.replace('bg-rose-500', 'bg-slate-800');
            btnFocus.classList.replace('hover:bg-rose-600', 'hover:bg-slate-900');
            if (document.fullscreenElement) document.exitFullscreen();
        }
    }

    function cariSantri() {
        let input = document.getElementById("searchInput").value.toLowerCase();
        let baris = document.querySelectorAll("#tbodySiswa .baris-santri");
        baris.forEach(row => {
            let namaSantri = row.querySelector(".nama-santri").innerText.toLowerCase();
            row.style.display = namaSantri.includes(input) ? "" : "none";
        });
    }

    function kalkulasiStatistik() {
        let ziyadah = 0, murojaah = 0, sangatLancar = 0;
        document.querySelectorAll('select[name="jenis_setoran[]"]').forEach(sel => {
            if(sel.value === 'Ziyadah') ziyadah++;
            else if(sel.value === 'Murojaah') murojaah++;
        });
        document.querySelectorAll('select[name="predikat[]"]').forEach(sel => {
            if(sel.value === 'Sangat Lancar') sangatLancar++;
        });
        document.getElementById('statZiyadah').innerText = ziyadah;
        document.getElementById('statMurojaah').innerText = murojaah;
        document.getElementById('statSangatLancar').innerText = sangatLancar;
    }

    function setSemuaLancar() {
        const jenisSelects = document.querySelectorAll('select[name="jenis_setoran[]"]');
        const predikatSelects = document.querySelectorAll('select[name="predikat[]"]');
        if(jenisSelects.length === 0) return;
        
        jenisSelects.forEach(select => select.value = 'Ziyadah');
        predikatSelects.forEach(select => select.value = 'Lancar');
        predikatSelects.forEach(select => updateColor(select));
        kalkulasiStatistik();
        
        Toast.fire({
            icon: 'success',
            title: LANG.toast_all_set
        });
    }

    function resetBaris(btnElem) {
        const row = btnElem.closest('tr');
        row.querySelector('input[name="surah[]"]').value = '';
        row.querySelector('input[name="ayat[]"]').value = '';
        row.querySelector('input[name="catatan[]"]').value = '';
        const selectJenis = row.querySelector('select[name="jenis_setoran[]"]');
        const selectPredikat = row.querySelector('select[name="predikat[]"]');
        selectJenis.value = 'Ziyadah';
        selectPredikat.value = 'Lancar';
        updateColor(selectPredikat);
        kalkulasiStatistik();
        
        row.classList.add('bg-rose-50');
        setTimeout(() => row.classList.remove('bg-rose-50'), 500);
    }

    async function loadSiswa() {
        const kelas_id = document.getElementById('kelasSelect').value;
        if(!kelas_id) { 
            Swal.fire({
                icon: 'info',
                title: 'Oops...',
                text: LANG.alert_select_class,
                buttonsStyling: false,
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'px-6 py-2.5 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-900 transition-colors' }
            });
            return; 
        }

        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('legendPenilaian').style.display = 'block';
        document.getElementById('searchInput').value = ''; 
        
        const tbody = document.getElementById('tbodySiswa');
        tbody.innerHTML = `<tr><td colspan="7" class="text-center p-12 text-slate-500 font-medium"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[${LANG.primary_color}] mx-auto mb-3"></div>${LANG.loading_data}</td></tr>`;

        try {
            const response = await fetch(`${LANG.fetch_url}?rombel_id=${kelas_id}`);
            const result = await response.json();

            if (result.status === 'success') {
                document.getElementById('statTotal').innerText = result.data.length;

                const getInitials = (name) => {
                    let initials = name.match(/\b\w/g) || [];
                    return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
                };
                
                let html = '';
                if(result.data.length === 0) {
                    html = `<tr><td colspan="7" class="text-center p-12 text-red-500 font-medium">${LANG.no_student}</td></tr>`;
                } else {
                    result.data.forEach((siswa, index) => {
                        html += `
                        <tr class="baris-santri group transition-all duration-200 hover:bg-slate-50 border-b border-slate-50 last:border-0 focus-within:bg-blue-50/40">
                            <td class="p-4 text-center text-sm font-medium text-slate-400 group-hover:text-slate-600">${index + 1}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs ring-1 ring-slate-200 shadow-sm flex-shrink-0">
                                        ${getInitials(siswa.nama_lengkap)}
                                    </div>
                                    <div>
                                        <p class="nama-santri font-semibold text-slate-800 text-sm transition-colors">${siswa.nama_lengkap}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5 tracking-wide">${LANG.nis_label} ${siswa.nis || '-'}</p>
                                        <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <select name="jenis_setoran[]" onchange="kalkulasiStatistik()" class="w-full text-sm font-semibold text-slate-700 border-0 ring-1 ring-slate-200 rounded-xl focus:ring-2 bg-slate-50 hover:bg-white transition-shadow py-2.5 cursor-pointer shadow-sm">
                                    <option value="Ziyadah">${LANG.type_ziyadah}</option>
                                    <option value="Murojaah">${LANG.type_murojaah}</option>
                                </select>
                            </td>
                            <td class="p-4 flex gap-2">
                                <input type="text" name="surah[]" list="listSurah" placeholder="${LANG.ph_surah}" class="w-3/5 text-sm font-medium border-0 ring-1 ring-slate-200 rounded-xl focus:ring-2 bg-white transition-shadow placeholder-slate-400 text-slate-800 py-2.5 px-3 shadow-sm">
                                <input type="text" name="ayat[]" placeholder="${LANG.ph_ayat}" class="w-2/5 text-sm font-medium border-0 ring-1 ring-slate-200 rounded-xl focus:ring-2 bg-white transition-shadow placeholder-slate-400 text-slate-800 py-2.5 px-3 shadow-sm">
                            </td>
                            <td class="p-4">
                                <select name="predikat[]" onchange="updateColor(this); kalkulasiStatistik();" class="w-full text-sm font-bold border-0 ring-1 ring-blue-200 rounded-xl focus:ring-2 focus:ring-blue-500 bg-blue-50 text-blue-700 py-2.5 transition-colors cursor-pointer shadow-sm text-center">
                                    <option value="Sangat Lancar" class="text-emerald-700 font-bold">${LANG.pred_very_fluent}</option>
                                    <option value="Lancar" selected class="text-blue-700 font-bold">${LANG.pred_fluent}</option>
                                    <option value="Kurang Lancar" class="text-amber-700 font-bold">${LANG.pred_poor}</option>
                                    <option value="Belum Hafal" class="text-red-700 font-bold">${LANG.pred_memorized}</option>
                                </select>
                            </td>
                            <td class="p-4">
                                <input type="text" name="catatan[]" placeholder="${LANG.ph_notes}" class="w-full text-sm font-medium border-0 border-b-2 border-transparent hover:border-slate-300 focus:ring-0 bg-transparent transition-colors placeholder-slate-400 px-2 py-2 text-slate-700">
                            </td>
                            <td class="p-4 text-center">
                                <button type="button" onclick="resetBaris(this)" title="${LANG.btn_clear}" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                }
                tbody.innerHTML = html;
                kalkulasiStatistik();
            }
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="7" class="text-center p-6 text-red-500">${LANG.alert_fetch_fail}</td></tr>`;
        }
    }

    function updateColor(selectElement) {
        const val = selectElement.value;
        selectElement.className = "w-full text-sm font-bold border-0 rounded-xl focus:ring-2 py-2.5 text-center transition-colors cursor-pointer shadow-sm ring-1 ";
        if(val === 'Sangat Lancar') selectElement.className += "bg-emerald-50 text-emerald-700 ring-emerald-200 focus:ring-emerald-500";
        else if(val === 'Lancar') selectElement.className += "bg-blue-50 text-blue-700 ring-blue-200 focus:ring-blue-500";
        else if(val === 'Kurang Lancar') selectElement.className += "bg-amber-50 text-amber-700 ring-amber-200 focus:ring-amber-500";
        else if(val === 'Belum Hafal') selectElement.className += "bg-red-50 text-red-700 ring-red-200 focus:ring-red-500";
    }

    async function simpanSetoran() {
        const form = document.getElementById('formSetoran');
        const formData = new FormData(form);
        formData.append('tanggal', document.getElementById('tanggalSetoran').value);

        const btn = document.querySelector('button[onclick="simpanSetoran()"]');
        const textSpan = document.getElementById('textBtnSaveAll');
        const originalText = textSpan.innerHTML;

        try {
            textSpan.innerHTML = LANG.saving;
            btn.disabled = true;

            Swal.fire({
                title: LANG.saving_title,
                text: LANG.saving_desc,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[2rem]' },
                didOpen: () => { Swal.showLoading(); }
            });

            const response = await fetch(LANG.save_url, {
                method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            textSpan.innerHTML = originalText;
            btn.disabled = false;

            if (result.status === 'success') {
                Swal.fire({
                    icon: 'success', title: LANG.success_title, text: result.message || LANG.success_default,
                    buttonsStyling: false, customClass: { popup: 'rounded-[2rem]', confirmButton: 'px-8 py-3 rounded-xl font-bold text-white bg-emerald-500 hover:bg-emerald-600 transition-all' }
                }).then(() => { loadSiswa(); });
            } else if (result.status === 'warning') {
                Swal.fire({
                    icon: 'warning', title: LANG.warning_title, text: result.message,
                    buttonsStyling: false, customClass: { popup: 'rounded-[2rem]', confirmButton: 'px-8 py-3 rounded-xl font-bold text-white bg-amber-500 hover:bg-amber-600 transition-all' }
                });
            } else {
                Swal.fire({
                    icon: 'error', title: LANG.error_title, text: result.message,
                    buttonsStyling: false, customClass: { popup: 'rounded-[2rem]', confirmButton: 'px-8 py-3 rounded-xl font-bold text-white bg-rose-500 hover:bg-rose-600 transition-all' }
                });
            }
        } catch (error) {
            textSpan.innerHTML = originalText;
            btn.disabled = false;
            Swal.fire({
                icon: 'error', title: LANG.server_error, text: LANG.server_error_desc,
                buttonsStyling: false, customClass: { popup: 'rounded-[2rem]', confirmButton: 'px-8 py-3 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-900 transition-all' }
            });
        }
    }