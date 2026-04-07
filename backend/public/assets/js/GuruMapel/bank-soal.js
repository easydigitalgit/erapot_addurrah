// ==========================================
// STATE MANAGEMENT
// ==========================================
let allQuestionsDB = []; 
let allPaketDB = [];
let currentFilter = { type: "all", difficulty: null };

// ==========================================
// INISIALISASI
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
    fetchQuestions();
    fetchPaketData();
});

async function fetchQuestions() {
    try {
        const response = await fetch(`${BASE_URL}/guru/bank-soal/get-data?mapel_id=${ACTIVE_MAPEL_ID}&tingkat=${ACTIVE_TINGKAT}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            allQuestionsDB = result.data;
            renderQuestionsTable();
            updateQuestionCount();
        }
    } catch (error) {
        console.error("Gagal mengambil data soal:", error);
    }
}

async function fetchPaketData() {
    try {
        const response = await fetch(`${BASE_URL}/guru/bank-soal/get-paket?mapel_id=${ACTIVE_MAPEL_ID}&tingkat=${ACTIVE_TINGKAT}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            allPaketDB = result.data;
            renderPaketTable();
        }
    } catch (error) {
        console.error("Gagal mengambil data paket:", error);
    }
}

// ==========================================
// RENDER TABEL SOAL
// ==========================================
function getVisibleQuestions() {
    const searchTerm = document.getElementById("searchInput").value.toLowerCase();
    return allQuestionsDB.filter((q) => {
        const typeMatch = currentFilter.type === "all" || q.jenis === currentFilter.type;
        const difficultyMatch = !currentFilter.difficulty || q.tingkat_kesulitan === currentFilter.difficulty;
        const searchMatch = q.pertanyaan.toLowerCase().includes(searchTerm) || q.kd.toLowerCase().includes(searchTerm);
        return typeMatch && difficultyMatch && searchMatch;
    });
}

function renderQuestionsTable() {
    const container = document.getElementById("questionsTableContainer");
    const emptyState = document.getElementById("emptyState");
    const filteredQuestions = getVisibleQuestions();

    if (filteredQuestions.length === 0) {
        container.style.display = "none";
        emptyState.style.display = "block";
        document.getElementById("emptyStateText").textContent = allQuestionsDB.length === 0 ? LANG.empty_first : LANG.empty_search;
        return;
    }

    container.style.display = "block";
    emptyState.style.display = "none";

    const typeLabels = { pg: LANG.type_pg, isian: LANG.type_short, esai: LANG.type_essay };
    const diffLabels = { mudah: LANG.diff_easy, sedang: LANG.diff_medium, sulit: LANG.diff_hard };

    let tbodyHTML = filteredQuestions.map((q, index) => {
        const snippet = q.pertanyaan.substring(0, 80) + (q.pertanyaan.length > 80 ? "..." : "");
        const lockedText = q.status === "terkunci" ? `<div class="flex items-center gap-1 mt-1.5 text-rose-600 dark:!text-rose-400 text-[10px] font-bold uppercase tracking-wider transition-colors"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg><span>${LANG.in_use}</span></div>` : "";
        
        let statusBadge = "";
        if(q.status === "aktif") statusBadge = `<span class="inline-flex px-2.5 py-1 bg-emerald-50 text-emerald-700 dark:!bg-emerald-900/30 dark:!text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-md border border-emerald-200 dark:!border-emerald-800/50 transition-colors shadow-sm">${LANG.status_active}</span>`;
        if(q.status === "sering") statusBadge = `<span class="inline-flex px-2.5 py-1 bg-amber-50 text-amber-700 dark:!bg-amber-900/30 dark:!text-amber-400 text-[10px] font-black uppercase tracking-wider rounded-md border border-amber-200 dark:!border-amber-800/50 transition-colors shadow-sm">${LANG.status_often}</span>`;
        if(q.status === "terkunci") statusBadge = `<span class="inline-flex px-2.5 py-1 bg-rose-50 text-rose-700 dark:!bg-rose-900/30 dark:!text-rose-400 text-[10px] font-black uppercase tracking-wider rounded-md border border-rose-200 dark:!border-rose-800/50 transition-colors shadow-sm">${LANG.status_locked}</span>`;

        // IMPLEMENTASI RBAC: Siapkan Action Buttons Default (Selalu bisa Preview)
        let actionButtons = `
            <button class="flex items-center justify-center p-2.5 bg-indigo-50 text-indigo-600 dark:!bg-indigo-900/30 dark:!text-indigo-400 rounded-lg hover:bg-indigo-100 dark:hover:!bg-indigo-900/50 transition-colors outline-none" onclick="showPreview(${q.id})" title="${LANG.btn_preview}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
        `;

        // IMPLEMENTASI RBAC: Tombol Hapus hanya muncul jika CAN_DELETE bernilai true
        if (typeof CAN_DELETE !== 'undefined' && CAN_DELETE) {
            actionButtons += `
            <button class="flex items-center justify-center p-2.5 bg-gray-100 text-gray-500 dark:!bg-slate-700 dark:!text-slate-400 hover:bg-rose-100 hover:text-rose-600 dark:hover:!bg-rose-900/50 dark:hover:!text-rose-400 rounded-lg transition-colors outline-none" onclick="deleteQuestion(${q.id})" title="${LANG.btn_delete}" ${q.status === 'terkunci' ? 'disabled style="opacity:0.4; cursor:not-allowed;"' : ''}>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>`;
        }

        return `
        <tr onclick="showPreview(${q.id})" class="cursor-pointer bg-white dark:!bg-slate-800 hover:bg-gray-50 dark:hover:!bg-slate-700/50 border-b border-gray-100 dark:!border-slate-700 transition-colors group">
            <td class="text-center py-4 px-4 transition-colors"><span class="font-bold text-gray-700 dark:!text-slate-400">${index + 1}</span></td>
            <td class="py-4 px-4 transition-colors">
                <p class="text-sm font-bold text-gray-900 dark:!text-white mb-0.5 leading-relaxed group-hover:text-[var(--warna-primary)] transition-colors">${snippet}</p>
                ${lockedText}
            </td>
            <td class="py-4 px-4 transition-colors"><span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-600 dark:!bg-slate-700 dark:!text-slate-300 text-[10px] font-black uppercase tracking-wider rounded-md border border-gray-200 dark:!border-slate-600 transition-colors">${typeLabels[q.jenis]}</span></td>
            <td class="py-4 px-4 transition-colors"><span class="inline-flex px-2.5 py-1 bg-blue-50 text-blue-600 dark:!bg-blue-900/30 dark:!text-blue-400 text-[10px] font-black uppercase tracking-wider rounded-md border border-blue-200 dark:!border-blue-800/50 transition-colors">${diffLabels[q.tingkat_kesulitan]}</span></td>
            <td class="py-4 px-4 transition-colors"><span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-600 dark:!bg-slate-700 dark:!text-slate-300 text-[10px] font-black uppercase tracking-wider rounded-md border border-gray-200 dark:!border-slate-600 transition-colors">${LANG.th_kd} ${q.kd}</span></td>
            <td class="py-4 px-4 transition-colors">${statusBadge}</td>
            <td class="py-4 px-4 transition-colors" onclick="event.stopPropagation()">
                <div class="flex gap-2">
                    ${actionButtons}
                </div>
            </td>
        </tr>`;
    }).join("");

    container.innerHTML = `
        <div class="overflow-x-auto w-full rounded-2xl border border-gray-200 dark:!border-slate-700 shadow-sm transition-colors custom-scrollbar">
            <table class="min-w-full whitespace-nowrap text-left border-collapse">
                <thead class="bg-gray-100 dark:!bg-slate-900 border-b border-gray-200 dark:!border-slate-700 transition-colors">
                    <tr>
                        <th class="px-4 py-4 text-center text-[11px] font-black text-gray-600 dark:!text-slate-300 uppercase tracking-widest w-12">${LANG.th_no}</th>
                        <th class="px-4 py-4 text-[11px] font-black text-gray-600 dark:!text-slate-300 uppercase tracking-widest">${LANG.th_snippet}</th>
                        <th class="px-4 py-4 text-[11px] font-black text-gray-600 dark:!text-slate-300 uppercase tracking-widest w-32">${LANG.th_type}</th>
                        <th class="px-4 py-4 text-[11px] font-black text-gray-600 dark:!text-slate-300 uppercase tracking-widest w-24">${LANG.th_diff}</th>
                        <th class="px-4 py-4 text-[11px] font-black text-gray-600 dark:!text-slate-300 uppercase tracking-widest w-20">${LANG.th_kd}</th>
                        <th class="px-4 py-4 text-[11px] font-black text-gray-600 dark:!text-slate-300 uppercase tracking-widest w-24">${LANG.th_status}</th>
                        <th class="px-4 py-4 text-[11px] font-black text-gray-600 dark:!text-slate-300 uppercase tracking-widest w-32">${LANG.th_action}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:!divide-slate-700/50 bg-white dark:!bg-slate-800 transition-colors">${tbodyHTML}</tbody>
            </table>
        </div>`;
}

// ==========================================
// RENDER KARTU PAKET SOAL
// ==========================================
function renderPaketTable() {
    const container = document.getElementById("paketSoalContainer");
    const emptyState = document.getElementById("emptyPaketState");
    container.innerHTML = "";

    if (allPaketDB.length === 0) {
        container.style.display = "none";
        emptyState.style.display = "block";
        return;
    }

    container.style.display = "grid";
    emptyState.style.display = "none";

    const locale = document.documentElement.lang || 'id-ID';

    allPaketDB.forEach((p) => {
        const tgl = new Date(p.tanggal).toLocaleDateString(locale, {day: 'numeric', month: 'short', year: 'numeric'});
        const jmlSoal = p.kumpulan_soal_id ? p.kumpulan_soal_id.split(',').length : 0;
        const targetKelasHTML = p.kelas_target.split(',').map(k => `<span class="px-2.5 py-1 bg-gray-100 text-gray-600 dark:!bg-slate-700 dark:!text-slate-300 text-[10px] font-black uppercase tracking-wider rounded-md border border-gray-200 dark:!border-slate-600 transition-colors">${k.trim()}</span>`).join('');
        
        const statusBadge = p.status === 'Terkunci' 
            ? `<span class="px-2.5 py-1 bg-rose-50 text-rose-700 dark:!bg-rose-900/30 dark:!text-rose-400 text-[10px] font-black uppercase tracking-wider rounded-md border border-rose-200 dark:!border-rose-800/50 flex items-center gap-1.5 transition-colors shadow-sm"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> ${LANG.status_locked} </span>`
            : `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 dark:!bg-emerald-900/30 dark:!text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-md border border-emerald-200 dark:!border-emerald-800/50 transition-colors shadow-sm">${LANG.status_active}</span>`;

        container.innerHTML += `
            <div class="bg-white dark:!bg-slate-800 border-2 border-gray-100 dark:!border-slate-700 p-5 rounded-3xl shadow-sm hover:border-[var(--warna-primary)] dark:hover:!border-[var(--warna-primary)] hover:shadow-md transition-all cursor-pointer flex flex-col justify-between group" onclick="showDetailPaket(${p.id})">
                <div class="flex items-start justify-between mb-5 gap-3">
                    <h3 class="font-black text-gray-900 dark:!text-white text-lg leading-tight group-hover:text-[var(--warna-primary)] transition-colors">${p.nama_paket}</h3>
                    <div class="flex-shrink-0">${statusBadge}</div>
                </div>
                <div>
                    <div class="flex items-center gap-4 text-sm mb-5 bg-gray-50 dark:!bg-slate-900/50 p-3 rounded-xl border border-gray-100 dark:!border-slate-700/50 transition-colors">
                        <div class="flex items-center gap-2 text-gray-700 dark:!text-slate-300 font-bold">
                            <svg class="w-4 h-4 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>${jmlSoal} ${LANG.total_questions}</span>
                        </div>
                        <div class="w-px h-4 bg-gray-300 dark:!bg-slate-600 transition-colors"></div>
                        <div class="flex items-center gap-2 text-gray-700 dark:!text-slate-300 font-bold">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>${tgl}</span>
                        </div>
                    </div>
                    <div class="flex gap-2 flex-wrap">${targetKelasHTML}</div>
                </div>
            </div>
        `;
    });
}

// ==========================================
// FUNGSI DETAIL PAKET & PRINT SOAL
// ==========================================
function showDetailPaket(id) {
    const paket = allPaketDB.find(p => parseInt(p.id) === parseInt(id));
    if(!paket) return;

    document.getElementById('detailPaketTitle').textContent = paket.nama_paket;

    const locale = document.documentElement.lang || 'id-ID';
    const tgl = new Date(paket.tanggal).toLocaleDateString(locale, {day: 'numeric', month: 'long', year: 'numeric'});
    const jmlSoal = paket.kumpulan_soal_id ? paket.kumpulan_soal_id.split(',').length : 0;
    
    document.getElementById('detailPaketInfo').innerHTML = `
        <div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> ${LANG.execution} <span class="font-black text-blue-900 dark:!text-blue-200">${tgl}</span></div>
        <div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg> ${LANG.class} <span class="font-black text-blue-900 dark:!text-blue-200">${paket.kelas_target}</span></div>
        <div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> ${LANG.total} <span class="font-black text-blue-900 dark:!text-blue-200">${jmlSoal} ${LANG.total_questions}</span></div>
    `;

    document.getElementById('btnPrintPaket').onclick = () => printPaket(id);

    const questionIds = paket.kumpulan_soal_id ? paket.kumpulan_soal_id.split(',').map(id => parseInt(id.trim())) : [];
    const paketQuestions = allQuestionsDB.filter(q => questionIds.includes(parseInt(q.id)));

    let qHtml = '';
    paketQuestions.forEach((q, idx) => {
        let optionsHtml = '';
        if(q.jenis === 'pg') {
            optionsHtml = `
                <div class="mt-4 ml-2 space-y-2.5 text-sm font-bold text-gray-700 dark:!text-slate-300 transition-colors">
                    <p class="flex gap-2"><span class="w-6 h-6 flex items-center justify-center bg-gray-100 dark:!bg-slate-700 rounded-lg text-xs flex-shrink-0">A</span> <span class="pt-0.5">${q.opsi_a.replace(/^A\.\s*/i, '')}</span></p>
                    <p class="flex gap-2"><span class="w-6 h-6 flex items-center justify-center bg-gray-100 dark:!bg-slate-700 rounded-lg text-xs flex-shrink-0">B</span> <span class="pt-0.5">${q.opsi_b.replace(/^B\.\s*/i, '')}</span></p>
                    ${q.opsi_c ? `<p class="flex gap-2"><span class="w-6 h-6 flex items-center justify-center bg-gray-100 dark:!bg-slate-700 rounded-lg text-xs flex-shrink-0">C</span> <span class="pt-0.5">${q.opsi_c.replace(/^C\.\s*/i, '')}</span></p>` : ''}
                    ${q.opsi_d ? `<p class="flex gap-2"><span class="w-6 h-6 flex items-center justify-center bg-gray-100 dark:!bg-slate-700 rounded-lg text-xs flex-shrink-0">D</span> <span class="pt-0.5">${q.opsi_d.replace(/^D\.\s*/i, '')}</span></p>` : ''}
                </div>
            `;
        }
        qHtml += `
            <div class="p-6 border-2 border-gray-100 dark:!border-slate-700 rounded-2xl bg-white dark:!bg-slate-800 transition-colors shadow-sm">
                <div class="flex gap-4">
                    <span class="font-black text-[var(--warna-primary)] text-xl w-6">${idx + 1}.</span>
                    <div class="flex-1">
                        <p class="font-bold text-gray-900 dark:!text-white leading-relaxed whitespace-pre-line text-base transition-colors">${q.pertanyaan}</p>
                        ${optionsHtml}
                        <div class="mt-6 p-4 bg-emerald-50 dark:!bg-emerald-900/20 rounded-xl border border-emerald-100 dark:!border-emerald-800/50 text-sm transition-colors">
                            <span class="font-black text-emerald-800 dark:!text-emerald-400 mr-2 uppercase tracking-widest text-[11px]">${LANG.ans_key}:</span> 
                            <span class="text-emerald-700 dark:!text-emerald-300 font-bold">${q.kunci_jawaban}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    document.getElementById('detailPaketQuestions').innerHTML = qHtml;
    
    const modal = document.getElementById('detailPaketModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeDetailPaketModal(event) {
    if (!event || event.target.id === "detailPaketModal") {
        const modal = document.getElementById("detailPaketModal");
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = "";
    }
}

function printPaket(id) {
    const paket = allPaketDB.find(p => parseInt(p.id) === parseInt(id));
    const questionIds = paket.kumpulan_soal_id ? paket.kumpulan_soal_id.split(',').map(idx => parseInt(idx.trim())) : [];
    const paketQuestions = allQuestionsDB.filter(q => questionIds.includes(parseInt(q.id)));
    const subjectName = document.getElementById('infoSubjectName').textContent;
    const locale = document.documentElement.lang || 'id-ID';

    let printWindow = window.open('', '_blank');
    let html = `
        <html>
        <head>
            <title>Print - ${paket.nama_paket}</title>
            <style>
                body { font-family: 'Times New Roman', Times, serif; line-height: 1.5; padding: 30px; color: #000; font-size: 12pt;}
                .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;}
                h2 { margin: 0 0 5px 0; text-transform: uppercase; font-size: 16pt;}
                .meta-table { width: 100%; margin-bottom: 30px; font-weight: bold; }
                .meta-table td { padding: 3px; }
                .question { margin-bottom: 25px; page-break-inside: avoid; display: flex; gap: 8px;}
                .question-num { font-weight: bold; }
                .question-text { flex: 1; }
                .options { margin-left: 0px; margin-top: 8px; }
                .options p { margin: 4px 0; }
                @media print {
                    @page { margin: 2cm; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>${LANG.print_title} ${paket.nama_paket}</h2>
            </div>
            
            <table class="meta-table">
                <tr>
                    <td width="15%">${LANG.print_subject}</td><td width="35%">: ${subjectName}</td>
                    <td width="15%">${LANG.print_student}</td><td width="35%">: ...........................................</td>
                </tr>
                <tr>
                    <td>${LANG.print_target}</td><td>: ${paket.kelas_target}</td>
                    <td>${LANG.print_absent}</td><td>: ...........................................</td>
                </tr>
                <tr>
                    <td>${LANG.print_date}</td><td>: ${new Date(paket.tanggal).toLocaleDateString(locale)}</td>
                    <td>${LANG.print_grade}</td><td>: </td>
                </tr>
            </table>
    `;

    paketQuestions.forEach((q, idx) => {
        html += `
            <div class="question">
                <div class="question-num">${idx + 1}.</div>
                <div class="question-text">
                    ${q.pertanyaan.replace(/\n/g, '<br>')}
        `;
        
        if(q.jenis === 'pg') {
            html += `
                <div class="options">
                    <p>${q.opsi_a}</p>
                    <p>${q.opsi_b}</p>
                    ${q.opsi_c ? `<p>${q.opsi_c}</p>` : ''}
                    ${q.opsi_d ? `<p>${q.opsi_d}</p>` : ''}
                </div>
            `;
        } else {
            html += `<br><br><br><br>`;
        }
        
        html += `</div></div>`;
    });

    html += `</body></html>`;
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
    }, 500);
}

// ==========================================
// FILTER & SEARCH LOGIC
// ==========================================
function filterByType(button, type) {
    document.querySelectorAll(".filter-button").forEach((btn) => {
        if (btn.textContent.includes(LANG.filter_all) || btn.textContent.includes(LANG.filter_pg) || btn.textContent.includes(LANG.filter_short) || btn.textContent.includes(LANG.filter_essay)) {
            btn.classList.remove("active");
        }
    });
    button.classList.add("active");
    currentFilter.type = type;
    renderQuestionsTable();
}

function filterByDifficulty(button, difficulty) {
    const difficultyButtons = Array.from(document.querySelectorAll(".filter-button")).filter(btn => btn.textContent.includes(LANG.filter_easy) || btn.textContent.includes(LANG.filter_medium) || btn.textContent.includes(LANG.filter_hard));

    if (button.classList.contains("active")) {
        button.classList.remove("active");
        currentFilter.difficulty = null;
    } else {
        difficultyButtons.forEach((btn) => btn.classList.remove("active"));
        button.classList.add("active");
        currentFilter.difficulty = difficulty;
    }
    renderQuestionsTable();
}

function filterQuestions() {
    renderQuestionsTable();
}

function updateQuestionCount() {
    document.getElementById("questionCount").textContent = `${allQuestionsDB.length} ${LANG.total_questions}`;
}

// ==========================================
// MODAL BUAT PAKET SOAL
// ==========================================
function showAddPaketModal() {
    const visibleQuestions = getVisibleQuestions();
    
    if (visibleQuestions.length === 0) {
        showToast(LANG.err_no_q, "error");
        return;
    }
    
    document.getElementById("paketSoalCount").textContent = visibleQuestions.length;
    document.getElementById("addPaketForm").reset();
    
    const modal = document.getElementById("addPaketModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.style.overflow = "hidden";
}

function closeAddPaketModal(event) {
    if (!event || event.target.id === "addPaketModal") {
        const modal = document.getElementById("addPaketModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        document.body.style.overflow = "";
    }
}

async function handleAddPaket(event) {
    event.preventDefault();
    
    const visibleQuestions = getVisibleQuestions();
    const arrSoalId = visibleQuestions.map(q => q.id).join(',');
    
    const namaPaket = document.getElementById("namaPaket").value;
    const tanggal = document.getElementById("tanggalPaket").value;
    const kelasTarget = document.getElementById("kelasTarget").value;

    const btn = event.target.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = `<svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.saving}`;
    btn.disabled = true;

    const formData = new FormData();
    formData.append('mapel_id', ACTIVE_MAPEL_ID);
    formData.append('tingkat', ACTIVE_TINGKAT);
    formData.append('nama_paket', namaPaket);
    formData.append('tanggal', tanggal);
    formData.append('kelas_target', kelasTarget);
    formData.append('kumpulan_soal_id', arrSoalId);
    formData.append(csrfTokenName, csrfTokenHash);

    try {
        const response = await fetch(`${BASE_URL}/guru/bank-soal/store-paket`, {
            method: 'POST',
            headers: { "X-Requested-With": "XMLHttpRequest" },
            body: formData
        });

        const result = await response.json();
        if(result.status === 'success') {
            showToast(LANG.succ_packet, "success");
            closeAddPaketModal();
            fetchPaketData(); 
        } else {
            showToast(`⚠️ Error: ${result.message}`, "error");
        }
    } catch (error) {
        showToast(LANG.err_server, "error");
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// ==========================================
// ADD QUESTION LOGIC
// ==========================================
function showAddQuestionModal() {
    const modal = document.getElementById("addQuestionModal");
    if(modal) {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        document.body.style.overflow = "hidden";
    }
}

function closeAddQuestionModal(event) {
    if (!event || event.target.id === "addQuestionModal") {
        const modal = document.getElementById("addQuestionModal");
        if(modal) {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
            document.body.style.overflow = "";
            document.getElementById("addQuestionForm").reset();
            document.getElementById("optionsSection").style.display = "none";
            document.getElementById("answerKeyPGSection").style.display = "none";
            document.getElementById("answerKeyTextSection").style.display = "none";
        }
    }
}

function handleQuestionTypeChange() {
    const type = document.getElementById("questionType").value;
    const optionsSection = document.getElementById("optionsSection");
    const answerKeyPGSection = document.getElementById("answerKeyPGSection");
    const answerKeyTextSection = document.getElementById("answerKeyTextSection");

    if (type === "pg") {
        optionsSection.style.display = "block";
        answerKeyPGSection.style.display = "block";
        answerKeyTextSection.style.display = "none";

        document.getElementById("optionA").required = true;
        document.getElementById("optionB").required = true;
        document.getElementById("answerKeyPG").required = true;
        document.getElementById("answerKeyText").required = false;
    } else if (type === "isian" || type === "esai") {
        optionsSection.style.display = "none";
        answerKeyPGSection.style.display = "none";
        answerKeyTextSection.style.display = "block";

        document.getElementById("optionA").required = false;
        document.getElementById("optionB").required = false;
        document.getElementById("answerKeyPG").required = false;
        document.getElementById("answerKeyText").required = true;
    } else {
        optionsSection.style.display = "none";
        answerKeyPGSection.style.display = "none";
        answerKeyTextSection.style.display = "none";
    }
}

async function handleAddQuestion(event) {
    event.preventDefault();

    const type = document.getElementById("questionType").value;
    const text = document.getElementById("questionText").value;
    const difficulty = document.getElementById("difficulty").value;
    const kd = document.getElementById("kd").value;
    const explanation = document.getElementById("explanation").value || LANG.no_exp;

    let answer = "";
    if (type === "pg") {
        const answerKey = document.getElementById("answerKeyPG").value;
        const optVal = document.getElementById(`option${answerKey}`).value;
        answer = `${answerKey}. ${optVal}`;
    } else {
        answer = document.getElementById("answerKeyText").value;
    }

    const formData = new FormData();
    formData.append('mapel_id', ACTIVE_MAPEL_ID);
    formData.append('tingkat', ACTIVE_TINGKAT);
    formData.append('jenis', type);
    formData.append('pertanyaan', text);
    formData.append('tingkat_kesulitan', difficulty);
    formData.append('kd', kd);
    formData.append('pembahasan', explanation);
    formData.append('kunci_jawaban', answer);
    formData.append(csrfTokenName, csrfTokenHash);

    if (type === "pg") {
        formData.append('opsi_a', `A. ${document.getElementById("optionA").value}`);
        formData.append('opsi_b', `B. ${document.getElementById("optionB").value}`);
        formData.append('opsi_c', `C. ${document.getElementById("optionC").value}`);
        formData.append('opsi_d', `D. ${document.getElementById("optionD").value}`);
    }

    const btn = event.target.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = `<svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.saving}`;
    btn.disabled = true;

    try {
        const response = await fetch(`${BASE_URL}/guru/bank-soal/store`, {
            method: 'POST',
            headers: { "X-Requested-With": "XMLHttpRequest" },
            body: formData
        });

        const result = await response.json();
        if(result.status === 'success') {
            showToast(LANG.succ_q, "success");
            closeAddQuestionModal();
            fetchQuestions(); 
        } else {
            showToast(`⚠️ Error: ${result.message}`, "error");
        }
    } catch (error) {
        showToast(LANG.err_server, "error");
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// ==========================================
// DELETE & PREVIEW SOAL LOGIC
// ==========================================
async function deleteQuestion(id) {
    if(!confirm(LANG.del_confirm)) return;

    try {
        const response = await fetch(`${BASE_URL}/guru/bank-soal/delete/${id}`, {
            method: 'POST',
            headers: { 
                "X-Requested-With": "XMLHttpRequest",
                "Content-Type": "application/json",
                [csrfTokenName]: csrfTokenHash
            }
        });
        const result = await response.json();
        if(result.status === 'success'){
            showToast(LANG.succ_del, "success");
            fetchQuestions(); 
        }
    } catch(e) {
        showToast(LANG.err_del, "error");
    }
}

function showPreview(id) {
    const question = allQuestionsDB.find((q) => parseInt(q.id) === parseInt(id));
    if (!question) {
        console.error(LANG.err_not_found);
        return;
    }

    const typeLabels = { pg: LANG.type_pg, isian: LANG.type_short, esai: LANG.type_essay };
    const diffLabels = { mudah: LANG.diff_easy, sedang: LANG.diff_medium, sulit: LANG.diff_hard };

    let contentHtml = `
        <div class="mb-6">
            <div class="flex gap-2 mb-6">
                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 dark:!bg-slate-700 dark:!text-slate-300 text-[10px] font-black uppercase tracking-wider rounded-md border border-gray-200 dark:!border-slate-600 transition-colors">${typeLabels[question.jenis]}</span>
                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 dark:!bg-blue-900/30 dark:!text-blue-400 text-[10px] font-black uppercase tracking-wider rounded-md border border-blue-200 dark:!border-blue-800/50 transition-colors">${diffLabels[question.tingkat_kesulitan]}</span>
                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 dark:!bg-slate-700 dark:!text-slate-300 text-[10px] font-black uppercase tracking-wider rounded-md border border-gray-200 dark:!border-slate-600 transition-colors">${LANG.th_kd} ${question.kd}</span>
            </div>
            <h3 class="text-[11px] font-black text-gray-500 dark:!text-slate-400 mb-3 border-b border-gray-200 dark:!border-slate-700 pb-2 uppercase tracking-widest transition-colors">${LANG.preview_q}</h3>
            <p class="text-gray-900 dark:!text-white font-bold mb-6 leading-relaxed whitespace-pre-line text-lg transition-colors">${question.pertanyaan}</p>
    `;

    if (question.jenis === "pg") {
        contentHtml += `
            <div class="mb-6">
                <h4 class="font-black text-gray-500 dark:!text-slate-400 mb-3 border-b border-gray-200 dark:!border-slate-700 pb-2 uppercase tracking-widest text-[11px] transition-colors">${LANG.preview_opts}:</h4>
                <div class="space-y-2 bg-gray-50 dark:!bg-slate-800/50 p-5 rounded-2xl border border-gray-200 dark:!border-slate-700 transition-colors">
                    <p class="text-gray-800 dark:!text-slate-300 font-bold transition-colors">${question.opsi_a}</p>
                    <p class="text-gray-800 dark:!text-slate-300 font-bold transition-colors">${question.opsi_b}</p>
                    ${question.opsi_c ? `<p class="text-gray-800 dark:!text-slate-300 font-bold transition-colors">${question.opsi_c}</p>` : ''}
                    ${question.opsi_d ? `<p class="text-gray-800 dark:!text-slate-300 font-bold transition-colors">${question.opsi_d}</p>` : ''}
                </div>
            </div>
        `;
    }

    contentHtml += `
            <div class="bg-emerald-50 dark:!bg-emerald-900/20 border-2 border-emerald-200 dark:!border-emerald-800/50 rounded-2xl p-5 mb-5 transition-colors">
                <h4 class="font-black text-emerald-900 dark:!text-emerald-400 mb-2 uppercase text-[11px] tracking-widest transition-colors">${LANG.ans_key}:</h4>
                <p class="text-emerald-800 dark:!text-emerald-300 font-black text-lg transition-colors">${question.kunci_jawaban}</p>
            </div>
            <div class="bg-blue-50 dark:!bg-blue-900/20 border-2 border-blue-200 dark:!border-blue-800/50 rounded-2xl p-5 transition-colors">
                <h4 class="font-black text-blue-900 dark:!text-blue-400 mb-2 uppercase text-[11px] tracking-widest transition-colors">${LANG.preview_exp}:</h4>
                <p class="text-blue-800 dark:!text-blue-300 font-semibold leading-relaxed transition-colors">${question.pembahasan || LANG.no_exp}</p>
            </div>
        </div>
    `;

    document.getElementById("previewContent").innerHTML = contentHtml;
    const modal = document.getElementById("previewModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.style.overflow = "hidden";
}

function closePreviewModal(event) {
    if (!event || event.target.id === "previewModal") {
        const modal = document.getElementById("previewModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        document.body.style.overflow = "";
    }
}

// ==========================================
// UTILITIES
// ==========================================
function showToast(message, type = 'success') {
  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toastMessage");
  if(!toast) return;
  
  toast.className = 'fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-5 py-4 bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0';
  
  const iconDiv = toast.querySelector('div');
  const svg = toast.querySelector('svg');

  if (type === 'error') {
      toast.classList.add('border-rose-500');
      if(iconDiv) iconDiv.className = 'w-8 h-8 rounded-full bg-rose-100 dark:!bg-rose-900/30 flex items-center justify-center flex-shrink-0 transition-colors';
      if(svg) {
        svg.className = 'w-5 h-5 text-rose-600 dark:!text-rose-400';
        svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
      }
  } else {
      toast.classList.add('border-emerald-500');
      if(iconDiv) iconDiv.className = 'w-8 h-8 rounded-full bg-emerald-100 dark:!bg-emerald-900/30 flex items-center justify-center flex-shrink-0 transition-colors';
      if(svg) {
        svg.className = 'w-5 h-5 text-emerald-600 dark:!text-emerald-400';
        svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
      }
  }

  if(toastMessage) toastMessage.textContent = message;
  
  requestAnimationFrame(() => {
      toast.classList.remove('translate-x-full', 'opacity-0');
  });

  setTimeout(() => {
      toast.classList.add('translate-x-full', 'opacity-0');
  }, 3500);
}

// ==========================================
// IMPORT CSV LOGIC
// ==========================================
function showImportModal() {
    const modal = document.getElementById("importModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.style.overflow = "hidden";
}

function closeImportModal(event) {
    if (!event || event.target.id === "importModal") {
        const modal = document.getElementById("importModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        document.body.style.overflow = "";
        document.getElementById("importForm").reset();
    }
}

async function handleImport(event) {
    event.preventDefault();
    
    const fileInput = document.getElementById("fileImport");
    if(fileInput.files.length === 0) return showToast(LANG.err_no_csv, "error");

    const btn = event.target.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = `<svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.processing}`;
    btn.disabled = true;

    const formData = new FormData();
    formData.append('file_import', fileInput.files[0]);
    formData.append('mapel_id', ACTIVE_MAPEL_ID);
    formData.append('tingkat', ACTIVE_TINGKAT);
    formData.append(csrfTokenName, csrfTokenHash);

    try {
        const response = await fetch(`${BASE_URL}/guru/bank-soal/import`, {
            method: 'POST',
            headers: { "X-Requested-With": "XMLHttpRequest" },
            body: formData
        });

        const result = await response.json();
        
        if(result.status === 'success') {
            showToast(`✓ ${result.message}`, "success");
            closeImportModal();
            fetchQuestions(); 
        } else {
            showToast(`⚠️ Error: ${result.message}`, "error");
        }
    } catch (error) {
        showToast(LANG.err_conn, "error");
        console.error(error);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}