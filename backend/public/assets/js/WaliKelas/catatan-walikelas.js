/**
 * File: public/assets/js/WaliKelas/catatan-walikelas.js
 */

let studentsData = typeof serverStudents !== "undefined" ? serverStudents : [];
let catatanWaliKelasData = typeof serverCatatan !== "undefined" ? serverCatatan : [];

document.addEventListener("DOMContentLoaded", function () {
    renderCatatanWaliKelas();
});

function renderCatatanWaliKelas() {
    const mainContent = document.getElementById("mainContent");
    const totalCatatan = catatanWaliKelasData.length;
    const catatanPositif = catatanWaliKelasData.filter((c) =>
        ["Akademik", "Bakat"].includes(c.category)
    ).length;
    const catatanPerhatian = catatanWaliKelasData.filter(
        (c) => c.priority === "Tinggi"
    ).length;

    mainContent.innerHTML = `
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-wider">${LANG.stat_total}</p>
                        <p class="text-3xl font-black text-gray-800 dark:text-white">${totalCatatan}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-wider">${LANG.stat_positive}</p>
                        <p class="text-3xl font-black text-green-600 dark:text-green-500">${catatanPositif}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-wider">${LANG.stat_warning}</p>
                        <p class="text-3xl font-black text-red-600 dark:text-red-500">${catatanPerhatian}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-wider">${LANG.stat_students}</p>
                        <p class="text-3xl font-black text-purple-600 dark:text-purple-500">${studentsData.length}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-2 mb-6 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-t-2xl overflow-x-auto p-2 custom-scrollbar">
            <button onclick="switchTab(event, 'overview')" class="tab-btn px-6 py-2.5 font-bold text-sm rounded-xl transition-colors bg-tema-light text-tema">
                ${LANG.tab_analytic}
            </button>
            <button onclick="switchTab(event, 'semua')" class="tab-btn px-6 py-2.5 font-bold text-sm rounded-xl transition-colors text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700/50">
                ${LANG.tab_board}
            </button>
        </div>

        <div id="tab-overview" class="tab-content block">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-black text-gray-800 dark:text-white mb-5">📊 ${LANG.chart_title}</h3>
                    <div class="space-y-4" id="categoryStats"></div>
                </div>
                <div class="bg-gradient-to-r from-emerald-50 dark:from-emerald-900/20 to-teal-50 dark:to-teal-900/20 border border-emerald-100 dark:border-emerald-800/30 rounded-3xl p-6 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 opacity-10 text-emerald-600 dark:text-emerald-500">
                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-black text-emerald-900 dark:text-emerald-400 mb-4 relative z-10">💡 ${LANG.rec_title}</h3>
                    <div class="space-y-3 text-sm text-emerald-800 dark:text-emerald-300 relative z-10">
                        <div class="flex gap-3 bg-white/60 dark:bg-slate-900/40 p-3 rounded-xl border border-white/50 dark:border-slate-700/50 backdrop-blur-sm">
                            <span class="font-black text-emerald-600 dark:text-emerald-400">1.</span>
                            <p>${LANG.rec_1_pt1} <span class="font-bold text-red-600 dark:text-red-400">${catatanPerhatian} ${LANG.stat_students}</span> ${LANG.rec_1_pt2}</p>
                        </div>
                        <div class="flex gap-3 bg-white/60 dark:bg-slate-900/40 p-3 rounded-xl border border-white/50 dark:border-slate-700/50 backdrop-blur-sm">
                            <span class="font-black text-emerald-600 dark:text-emerald-400">2.</span>
                            <p>${LANG.rec_2}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-semua" class="tab-content hidden">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <input type="text" id="searchCWK" placeholder="${LANG.search_ph}" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-gray-800 dark:text-slate-200 focus-tema w-full transition-all" onkeyup="filterCatatan()">
                    <select id="filterCategory" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-gray-800 dark:text-slate-200 focus-tema w-full cursor-pointer transition-all" onchange="filterCatatan()">
                        <option value="">${LANG.filter_cat_all}</option>
                        <option value="Akademik">${LANG.filter_cat_academic}</option>
                        <option value="Sosial">${LANG.filter_cat_social}</option>
                        <option value="Bakat">${LANG.filter_cat_talent}</option>
                    </select>
                    <select id="filterPriority" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-gray-800 dark:text-slate-200 focus-tema w-full cursor-pointer transition-all" onchange="filterCatatan()">
                        <option value="">${LANG.filter_pri_all}</option>
                        <option value="Rendah">${LANG.filter_pri_low}</option>
                        <option value="Sedang">${LANG.filter_pri_med}</option>
                        <option value="Tinggi">${LANG.filter_pri_high}</option>
                    </select>
                </div>
            </div>
            <div id="catatanList" class="grid grid-cols-1 md:grid-cols-2 gap-5"></div>
        </div>

 ${
   typeof CAN_CREATE !== "undefined" && CAN_CREATE
     ? `
        <div class="fixed bottom-6 right-6 z-30">
            <button onclick="openFormModal()" class="px-6 py-3.5 bg-tema text-white font-bold rounded-2xl shadow-lg shadow-[var(--warna-primary)]/40 hover:-translate-y-1 transition-transform flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden sm:inline tracking-wide">${LANG.btn_add_new}</span>
            </button>
        </div>`
     : ""
 }
    `;

    renderCategoryStats();
    renderCatatanList();
}

function renderCategoryStats() {
    const stats = {};
    catatanWaliKelasData.forEach(
        (c) => (stats[c.category] = (stats[c.category] || 0) + 1)
    );

    const container = document.getElementById("categoryStats");
    if (Object.keys(stats).length === 0) {
        container.innerHTML = `<p class="text-sm text-gray-400 dark:text-slate-500 font-medium">${LANG.no_data_chart}</p>`;
        return;
    }

    container.innerHTML = Object.entries(stats)
        .map(([cat, count]) => {
            const pct = Math.round((count / catatanWaliKelasData.length) * 100);
            
            // Translate the category names for display
            let displayCat = cat;
            if(cat === 'Akademik') displayCat = LANG.filter_cat_academic;
            if(cat === 'Sosial') displayCat = LANG.filter_cat_social;
            if(cat === 'Bakat') displayCat = LANG.filter_cat_talent;

            return `
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300">${displayCat}</span>
                    <span class="text-xs font-black text-gray-500 dark:text-slate-400 bg-gray-100 dark:bg-slate-700 px-2 py-0.5 rounded-md">${count} ${LANG.chart_student}</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-tema h-full rounded-full transition-all duration-1000 ease-out" style="width: ${pct}%"></div>
                </div>
            </div>
        `;
        })
        .join("");
}

function renderCatatanList() {
    const container = document.getElementById("catatanList");
    if (!container) return;

    const search = document.getElementById("searchCWK")?.value.toLowerCase() || "";
    const category = document.getElementById("filterCategory")?.value || "";
    const priority = document.getElementById("filterPriority")?.value || "";

    const filtered = catatanWaliKelasData.filter((c) => {
        return (
            (!search || c.studentName.toLowerCase().includes(search)) &&
            (!category || c.category === category) &&
            (!priority || c.priority === priority)
        );
    });

    if (filtered.length === 0) {
        container.innerHTML = `<div class="col-span-full text-center py-10"><p class="text-gray-400 dark:text-slate-500 font-bold">${LANG.no_data_filter}</p></div>`;
        return;
    }

    container.innerHTML = filtered.map(c => {
        const pColor = c.priority === 'Tinggi' ? 'red' : c.priority === 'Sedang' ? 'amber' : 'emerald';
        
        // Translate for display only
        let displayCat = c.category;
        if(displayCat === 'Akademik') displayCat = LANG.filter_cat_academic;
        if(displayCat === 'Sosial') displayCat = LANG.filter_cat_social;
        if(displayCat === 'Bakat') displayCat = LANG.filter_cat_talent;

        let displayPri = c.priority;
        if(displayPri === 'Rendah') displayPri = LANG.filter_pri_low;
        if(displayPri === 'Sedang') displayPri = LANG.filter_pri_med;
        if(displayPri === 'Tinggi') displayPri = LANG.filter_pri_high;

        return `
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 hover:shadow-lg transition-shadow relative overflow-hidden flex flex-col h-full group">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-${pColor}-400 dark:bg-${pColor}-600 group-hover:h-2 transition-all"></div>
                
                <div class="flex justify-between items-start mb-4 mt-1">
                    <div>
                        <h3 class="text-lg font-black text-gray-800 dark:text-white truncate" title="${c.studentName}">${c.studentName}</h3>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">${c.date}</p>
                    </div>
                    <div class="flex gap-1.5 flex-col items-end">
                        <span class="px-2.5 py-1 text-[9px] font-black rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 uppercase tracking-wider">${displayCat}</span>
                        <span class="px-2.5 py-1 text-[9px] font-black rounded-lg bg-${pColor}-50 dark:bg-${pColor}-900/30 text-${pColor}-600 dark:text-${pColor}-400 uppercase tracking-wider">${displayPri}</span>
                    </div>
                </div>
                
                <div class="mb-4 flex-grow">
                    <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        ${LANG.lbl_note}
                    </p>
                    <p class="text-sm text-gray-700 dark:text-slate-300 leading-relaxed italic bg-gray-50 dark:bg-slate-900/50 p-3 rounded-xl border border-gray-100 dark:border-slate-700/50">"${c.note}"</p>
                </div>
                
                <div class="bg-gray-50 dark:bg-slate-900/30 p-4 rounded-2xl mb-5 border border-gray-100 dark:border-slate-700">
                    <p class="text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1.5">${LANG.lbl_followup}</p>
                    <p class="text-xs text-gray-700 dark:text-slate-300 font-medium">${c.followUp}</p>
                </div>
                
                <div class="flex gap-2 pt-4 border-t border-gray-100 dark:border-slate-700 mt-auto">
                    ${(typeof CAN_UPDATE !== 'undefined' && CAN_UPDATE) ? `
                    <button onclick="openFormModal(${c.id})" class="flex-1 py-2.5 px-4 bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-tema hover:text-white dark:hover:bg-tema transition-colors text-sm border border-gray-200 dark:border-slate-600 hover:border-transparent shadow-sm">
                        ${LANG.btn_update}
                    </button>` : ''}

                    ${(typeof CAN_DELETE !== 'undefined' && CAN_DELETE) ? `
                    <button onclick="confirmDelete(${c.id})" class="py-2.5 px-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-bold rounded-xl hover:bg-red-600 hover:text-white dark:hover:bg-red-600 transition-colors text-sm border border-red-100 dark:border-red-800/30 hover:border-transparent shadow-sm">
                        ${LANG.btn_delete}
                    </button>` : ''}
                </div>
            </div>
        `;
    }).join('');
}

function switchTab(event, tabName) {
    document.querySelectorAll(".tab-content").forEach((t) => t.classList.add("hidden"));
    document.querySelectorAll(".tab-btn").forEach((btn) => {
        btn.classList.remove("bg-tema-light", "text-tema");
        btn.classList.add("text-gray-500", "dark:text-slate-400");
    });

    document.getElementById(`tab-${tabName}`).classList.remove("hidden");
    event.currentTarget.classList.remove("text-gray-500", "dark:text-slate-400");
    event.currentTarget.classList.add("bg-tema-light", "text-tema");

    if (tabName === "semua") renderCatatanList();
}

function filterCatatan() { renderCatatanList(); }

function openFormModal(editId = null) {
    const isEdit = editId !== null;
    let data = {
        studentId: "",
        category: "Akademik",
        priority: "Rendah",
        status: "Baru",
        note: "",
        followUp: "",
        date: new Date().toISOString().split("T")[0],
    };

    if (isEdit) {
        const found = catatanWaliKelasData.find((c) => c.id === editId);
        if (found) data = { ...found };
    }

    const modal = document.createElement("div");
    modal.className = "fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[60] flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300";
    modal.id = "formModal";
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-xl w-full transform scale-95 transition-transform duration-300 overflow-hidden" id="modalContent">
            <div class="px-6 lg:px-8 py-6 flex items-center justify-between text-white bg-tema shadow-md z-10" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);">
                <div>
                    <h2 class="text-xl lg:text-2xl font-black tracking-tight">${isEdit ? LANG.modal_title_edit : LANG.modal_title_add}</h2>
                    <p class="text-xs opacity-90 mt-1 font-medium tracking-widest uppercase">${LANG.modal_subtitle}</p>
                </div>
                <button onclick="closeFormModal()" class="p-2 hover:bg-white/20 rounded-2xl transition-colors border border-transparent hover:border-white/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6 lg:p-8 bg-gray-50/50 dark:bg-slate-900/50 max-h-[75vh] overflow-y-auto custom-scrollbar">
                <form onsubmit="saveCatatan(event, ${editId})">
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2 uppercase tracking-wider">${LANG.form_student}</label>
                        <select id="f_student" required class="w-full px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-xl font-bold text-gray-800 dark:text-slate-200 focus-tema bg-white dark:bg-slate-800 shadow-sm transition-all cursor-pointer">
                            <option value="">-- ${LANG.form_student_ph} --</option>
                            ${studentsData.map((s) => `<option value="${s.id}" ${data.studentId == s.id ? "selected" : ""}>${s.name}</option>`).join("")}
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2 uppercase tracking-wider">${LANG.form_category}</label>
                            <select id="f_category" class="w-full px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-xl font-bold text-gray-800 dark:text-slate-200 focus-tema bg-white dark:bg-slate-800 shadow-sm transition-all cursor-pointer">
                                <option value="Akademik" ${data.category === "Akademik" ? "selected" : ""}>${LANG.filter_cat_academic}</option>
                                <option value="Sosial" ${data.category === "Sosial" ? "selected" : ""}>${LANG.filter_cat_social}</option>
                                <option value="Bakat" ${data.category === "Bakat" ? "selected" : ""}>${LANG.filter_cat_talent}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2 uppercase tracking-wider">${LANG.form_priority}</label>
                            <select id="f_priority" class="w-full px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-xl font-bold text-gray-800 dark:text-slate-200 focus-tema bg-white dark:bg-slate-800 shadow-sm transition-all cursor-pointer">
                                <option value="Rendah" ${data.priority === "Rendah" ? "selected" : ""}>${LANG.filter_pri_low}</option>
                                <option value="Sedang" ${data.priority === "Sedang" ? "selected" : ""}>${LANG.filter_pri_med}</option>
                                <option value="Tinggi" ${data.priority === "Tinggi" ? "selected" : ""}>${LANG.filter_pri_high}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-tema" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            ${LANG.lbl_note}
                        </label>
                        <textarea id="f_note" required rows="4" class="w-full px-4 py-3.5 border border-gray-200 dark:border-slate-700 rounded-xl font-medium text-gray-800 dark:text-slate-200 focus-tema bg-white dark:bg-slate-800 resize-none shadow-sm transition-all" placeholder="${LANG.form_note_ph}">${data.note}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-2 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            ${LANG.lbl_followup}
                        </label>
                        <textarea id="f_followup" rows="2" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-medium text-gray-800 dark:text-slate-200 focus-tema bg-white dark:bg-slate-800 resize-none shadow-sm transition-all" placeholder="${LANG.form_followup_ph}">${data.followUp}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                        <button type="button" onclick="closeFormModal()" class="px-6 py-3 border-2 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">${LANG.btn_cancel}</button>
                        <button type="submit" class="px-8 py-3 text-white font-bold rounded-xl shadow-lg shadow-[var(--warna-primary)]/30 hover:shadow-[var(--warna-primary)]/50 transition-all transform hover:-translate-y-0.5 bg-tema flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            ${LANG.btn_save}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    setTimeout(() => {
        modal.classList.remove("opacity-0");
        document.getElementById("modalContent").classList.remove("scale-95");
    }, 10);
}

function closeFormModal() {
    const modal = document.getElementById("formModal");
    if (!modal) return;
    modal.classList.add("opacity-0");
    document.getElementById("modalContent").classList.add("scale-95");
    setTimeout(() => modal.remove(), 300);
}

async function saveCatatan(e, editId) {
    e.preventDefault();
    const payload = {
        id: editId !== null ? editId : null,
        studentId: document.getElementById("f_student").value,
        category: document.getElementById("f_category").value,
        priority: document.getElementById("f_priority").value,
        status: "Baru",
        note: document.getElementById("f_note").value,
        followUp: document.getElementById("f_followup").value,
    };

    try {
        const response = await fetch(BASE_URL + "/wali/catatan-walikelas/save", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload),
        });

        if (response.ok) {
            closeFormModal();
            showToast(LANG.succ_saved, "emerald");
            setTimeout(() => window.location.reload(), 1000);
        }
    } catch (err) {
        console.error("Gagal simpan:", err);
    }
}

function confirmDelete(id) {
    const modal = document.createElement("div");
    modal.className = "fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[70] flex items-center justify-center p-4 opacity-0 transition-opacity duration-300";
    modal.id = "deleteModal";
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 transition-transform duration-300" id="deleteModalContent">
            <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-900/30 border border-red-100 dark:border-red-800/50 text-red-600 dark:text-red-500 flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2">${LANG.del_title}</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mb-6">${LANG.del_desc}</p>
            <div class="flex gap-3 justify-center">
                <button onclick="document.getElementById('deleteModal').remove()" class="px-6 py-2.5 border-2 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">${LANG.btn_cancel}</button>
                <button onclick="executeDelete(${id})" class="px-6 py-2.5 bg-red-600 dark:bg-red-700 text-white font-bold rounded-xl shadow-md hover:bg-red-700 transition-colors">${LANG.btn_yes_delete}</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    setTimeout(() => {
        modal.classList.remove("opacity-0");
        document.getElementById("deleteModalContent").classList.remove("scale-95");
    }, 10);
}

async function executeDelete(id) {
    try {
        const response = await fetch(BASE_URL + "/wali/catatan-walikelas/delete", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: id }),
        });

        if (response.ok) {
            document.getElementById("deleteModal").remove();
            showToast(LANG.succ_deleted, "slate");
            setTimeout(() => window.location.reload(), 1000);
        }
    } catch (err) {
        console.error("Gagal hapus:", err);
    }
}

// 5. TOAST NOTIFICATION
function showToast(message, colorClass = "emerald") {
    const toast = document.createElement("div");

    let gradient = "from-emerald-500 to-teal-600";
    if (colorClass === "red") gradient = "from-red-500 to-rose-600";
    if (colorClass === "amber") gradient = "from-amber-500 to-orange-600";
    if (colorClass === "slate") gradient = "from-slate-600 to-slate-800";
    if (colorClass === "tema") gradient = "from-[var(--warna-primary)] to-[var(--warna-primary)]"; 

    toast.className = `fixed bottom-6 right-6 bg-gradient-to-r ${gradient} text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 z-[100] transform translate-y-20 opacity-0 transition-all duration-500 border border-white/10`;
    toast.innerHTML = `
        <svg class="w-6 h-6 flex-shrink-0 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-bold text-sm tracking-wide drop-shadow-sm">${message}</span>
    `;
    document.body.appendChild(toast);

    setTimeout(() => { toast.classList.remove("translate-y-20", "opacity-0"); }, 10);
    setTimeout(() => {
        toast.classList.add("translate-y-20", "opacity-0");
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}