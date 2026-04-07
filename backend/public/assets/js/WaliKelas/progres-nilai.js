/**
 * File: public/assets/js/WaliKelas/progres-nilai.js
 */

const subjectsData = window.dynamicSubjectsData || [];
const studentsData = window.dynamicStudentsData || [];
let currentViewMode = 'grid';

document.addEventListener('DOMContentLoaded', function() {
  if (subjectsData.length > 0) {
      renderChart(subjectsData);
      renderGrid(subjectsData);
      renderSubjectSelector(subjectsData);
      renderAnalisisTren(subjectsData);
      populateSubjectFilter();
  } else {
      document.getElementById('chartContainer').innerHTML = `<p class="text-gray-400 w-full text-center pb-10">${LANG.no_data}</p>`;
      document.getElementById('gridView').innerHTML = `<p class="text-gray-400 w-full text-center pb-10">${LANG.no_detail}</p>`;
  }
});

// =================== 1. CHART & FILTER ===================
function renderChart(data) {
  const chartContainer = document.getElementById('chartContainer');
  const legend = document.getElementById('legend');
  if(!chartContainer || !legend) return;
  
  chartContainer.innerHTML = '';
  legend.innerHTML = '';

  data.forEach((subject) => {
    const barHeight = (subject.average / 100) * 100; 
    
    const barItem = document.createElement('div');
    barItem.className = 'bar-item cursor-pointer transform hover:scale-105 transition-all';
    barItem.onclick = () => jumpToDetail(subject.name);
    
    barItem.innerHTML = `
      <div class="bar shadow-sm relative group" style="height: ${barHeight}%; background: linear-gradient(180deg, ${subject.color} 0%, ${subject.color}80 100%);">
        <span class="bar-value shadow-md">${subject.average}</span>
      </div>
      <span class="text-2xl mt-3" title="${subject.name}">${subject.icon}</span>
    `;
    chartContainer.appendChild(barItem);

    const legendItem = document.createElement('div');
    legendItem.className = 'flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-full border border-gray-100 cursor-pointer hover:bg-gray-100 transition-colors';
    legendItem.onclick = () => jumpToDetail(subject.name);
    legendItem.innerHTML = `
      <div class="w-3 h-3 rounded-full shadow-sm" style="background-color: ${subject.color}"></div>
      <p class="text-xs text-gray-700 font-semibold truncate max-w-[120px]" title="${subject.name}">${subject.name}</p>
    `;
    legend.appendChild(legendItem);
  });
}

function populateSubjectFilter() {
    const select = document.getElementById('subjectFilter');
    if (!select) return;
    // Pilihan "Semua Mapel" sudah ada di HTML, tidak perlu ditimpa total. Kita tambahkan opsi lain.
    subjectsData.forEach(sub => select.innerHTML += `<option value="${sub.name}">${sub.name}</option>`);
}

function filterData() {
    const subjectFilter = document.getElementById('subjectFilter')?.value || "";
    const statusFilter = document.getElementById('statusFilter')?.value || "";
    const sortFilter = document.getElementById('sortFilter')?.value || "subject";

    let filteredData = subjectsData.filter(sub => 
        (subjectFilter === "" || sub.name === subjectFilter) && 
        (statusFilter === "" || sub.status === statusFilter)
    );

    filteredData.sort((a, b) => {
        if (sortFilter === 'average-desc') return b.average - a.average; 
        if (sortFilter === 'average-asc') return a.average - b.average; 
        return a.name.localeCompare(b.name); 
    });

    if (filteredData.length > 0) {
        renderChart(filteredData);
        renderGrid(filteredData);
    } else {
        document.getElementById('chartContainer').innerHTML = `<p class="text-gray-400 w-full text-center pb-10">${LANG.no_data}</p>`;
        document.getElementById('gridView').innerHTML = `<p class="text-gray-400 w-full text-center py-6 col-span-3">${LANG.no_data}</p>`;
        document.getElementById('tableBody').innerHTML = `<tr><td colspan="6" class="text-center py-6 text-gray-500">${LANG.no_data}</td></tr>`;
    }
}

function renderGrid(data) {
  const gridView = document.getElementById('gridView');
  const tableBody = document.getElementById('tableBody');
  if(!gridView || !tableBody) return;
  
  gridView.innerHTML = ''; tableBody.innerHTML = '';

  data.forEach((subject) => {
    const statusColor = subject.status === 'Aman' ? 'emerald' : (subject.status === 'Rawan' ? 'amber' : (subject.status === 'Belum Dinilai' ? 'slate' : 'red'));
    const trendIcon = subject.trend === 'up' ? '<svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>' : (subject.trend === 'down' ? '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>' : '<svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path></svg>');

    const card = document.createElement('div');
    card.className = 'bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 cursor-pointer group';
    card.onclick = () => jumpToDetail(subject.name);
    card.innerHTML = `
      <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shadow-inner bg-gray-50 dark:bg-slate-900 border border-gray-100 dark:border-slate-700 group-hover:scale-110 transition-transform">${subject.icon}</div>
          <h3 class="font-bold text-gray-800 dark:text-slate-100 text-lg truncate pr-2 group-hover:text-tema transition-colors">${subject.name}</h3>
        </div>
        <span class="px-2.5 py-1 bg-${statusColor}-50 dark:bg-${statusColor}-900/20 text-${statusColor}-600 dark:text-${statusColor}-400 text-[10px] font-extrabold uppercase tracking-wider rounded-md border border-${statusColor}-200 dark:border-${statusColor}-800/50 shadow-sm">${subject.status}</span>
      </div>
      
      <div class="grid grid-cols-3 gap-2 mb-5 pb-5 border-b border-gray-100 dark:border-slate-700">
        <div class="text-center bg-gray-50 dark:bg-slate-900/50 py-2 rounded-lg border border-gray-100 dark:border-slate-700/50"><p class="text-[9px] text-gray-500 dark:text-slate-400 uppercase font-bold tracking-wider mb-0.5">Rata-rata</p><p class="text-xl font-black text-gray-800 dark:text-white">${subject.average}</p></div>
        <div class="text-center bg-emerald-50 dark:bg-emerald-900/10 py-2 rounded-lg border border-emerald-100 dark:border-emerald-900/30"><p class="text-[9px] text-emerald-600 dark:text-emerald-500 uppercase font-bold tracking-wider mb-0.5">Tertinggi</p><p class="text-xl font-black text-emerald-600 dark:text-emerald-400">${subject.highest}</p></div>
        <div class="text-center bg-red-50 dark:bg-red-900/10 py-2 rounded-lg border border-red-100 dark:border-red-900/30"><p class="text-[9px] text-red-600 dark:text-red-500 uppercase font-bold tracking-wider mb-0.5">Terendah</p><p class="text-xl font-black text-red-600 dark:text-red-400">${subject.lowest}</p></div>
      </div>
      
      <div class="flex items-center gap-3">
        <div class="progress-bar flex-1 h-1.5 bg-gray-100 dark:bg-slate-700 rounded-full overflow-hidden">
          <div class="progress-fill h-full rounded-full transition-all duration-1000 ease-out shadow-sm" style="width: ${subject.average}%; background: ${subject.color};"></div>
        </div>
        <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 group-hover:text-tema transition-colors flex items-center gap-1">${LANG.lbl_detail} <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg></span>
      </div>
    `;
    gridView.appendChild(card);

    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer group';
    row.onclick = card.onclick;
    row.innerHTML = `
      <td class="px-6 py-4 border-b border-gray-100 dark:border-slate-700">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center text-lg shadow-sm border border-gray-100 dark:border-slate-600 bg-white dark:bg-slate-800">${subject.icon}</div>
          <p class="font-bold text-gray-800 dark:text-slate-200 group-hover:text-tema transition-colors">${subject.name}</p>
        </div>
      </td>
      <td class="px-6 py-4 text-center border-b border-gray-100 dark:border-slate-700"><span class="font-black text-lg text-gray-800 dark:text-white">${subject.average}</span></td>
      <td class="px-6 py-4 text-center border-b border-gray-100 dark:border-slate-700"><span class="font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded">${subject.highest}</span></td>
      <td class="px-6 py-4 text-center border-b border-gray-100 dark:border-slate-700"><span class="font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded">${subject.lowest}</span></td>
      <td class="px-6 py-4 text-center border-b border-gray-100 dark:border-slate-700 flex justify-center">${trendIcon}</td>
      <td class="px-6 py-4 text-center border-b border-gray-100 dark:border-slate-700"><span class="inline-block px-3 py-1 bg-${statusColor}-50 dark:bg-${statusColor}-900/20 text-${statusColor}-600 dark:text-${statusColor}-400 text-[10px] rounded-md font-extrabold uppercase tracking-wider border border-${statusColor}-200 dark:border-${statusColor}-800/50">${subject.status}</span></td>
    `;
    tableBody.appendChild(row);
  });
}

function renderSubjectSelector(data) {
  const select = document.getElementById('subjectSelect');
  if(!select) return;
  select.innerHTML = '';
  data.forEach((subject) => {
    const btn = document.createElement('button');
    btn.className = 'p-3 bg-white border border-gray-200 hover:border-tema hover:shadow-md rounded-xl text-center transition-all group';
    btn.innerHTML = `<div class="text-2xl mb-1 transform group-hover:-translate-y-1 transition-transform">${subject.icon}</div><p class="text-[10px] font-bold text-gray-600 group-hover:text-tema truncate" title="${subject.name}">${subject.name}</p>`;
    btn.onclick = () => showSubjectDetail(subject.name);
    select.appendChild(btn);
  });
}

function showSubjectDetail(subjectName) {
  const subject = subjectsData.find(s => s.name === subjectName);
  if(!subject) return;

  const panel = document.getElementById('detailPanel');
  const color = subject.status === 'Aman' ? 'emerald' : (subject.status === 'Rawan' ? 'amber' : (subject.status === 'Belum Dinilai' ? 'slate' : 'red'));

  let recommendation = '';
  if (subject.status === 'Aman') recommendation = LANG.rec_aman.replace('{name}', subject.name).replace('{avg}', subject.average);
  else if (subject.status === 'Rawan') recommendation = LANG.rec_rawan.replace('{name}', subject.name).replace('{avg}', subject.average);
  else if (subject.status === 'Belum Dinilai') recommendation = LANG.rec_belum.replace('{name}', subject.name);
  else recommendation = LANG.rec_kritis.replace('{name}', subject.name).replace('{avg}', subject.average);

  panel.innerHTML = `
    <div class="animate-fade-in">
      <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-gray-100 dark:border-slate-600" style="background-color: ${subject.color}15">
            ${subject.icon}
          </div>
          <div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-slate-100">${subject.name}</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 font-medium">Analisis Detail Capaian Nilai</p>
          </div>
        </div>
        <span class="px-4 py-1.5 bg-${color}-100 dark:bg-${color}-900/30 text-${color}-700 dark:text-${color}-400 rounded-full font-bold text-sm tracking-wider uppercase border border-${color}-200 dark:border-${color}-800/50 shadow-sm">${subject.status}</span>
      </div>

      <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-50 dark:bg-slate-900 border border-gray-100 dark:border-slate-700 p-4 rounded-xl text-center"><p class="text-[10px] text-gray-500 dark:text-slate-400 font-bold uppercase mb-1">Rata-rata</p><p class="text-3xl font-black text-gray-800 dark:text-white">${subject.average}</p></div>
        <div class="bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30 p-4 rounded-xl text-center"><p class="text-[10px] text-emerald-600 dark:text-emerald-500 font-bold uppercase mb-1">Tertinggi</p><p class="text-3xl font-black text-emerald-600 dark:text-emerald-400">${subject.highest}</p></div>
        <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 p-4 rounded-xl text-center"><p class="text-[10px] text-red-600 dark:text-red-500 font-bold uppercase mb-1">Terendah</p><p class="text-3xl font-black text-red-600 dark:text-red-400">${subject.lowest}</p></div>
        <div class="bg-purple-50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-900/30 p-4 rounded-xl text-center"><p class="text-[10px] text-purple-600 dark:text-purple-500 font-bold uppercase mb-1">Range Jarak</p><p class="text-3xl font-black text-purple-600 dark:text-purple-400">${subject.highest - subject.lowest}</p></div>
      </div>

      <div class="bg-${color}-50 dark:bg-${color}-900/10 border border-${color}-200 dark:border-${color}-800/30 rounded-xl p-5 mb-2 shadow-sm">
        <h4 class="font-bold text-${color}-900 dark:text-${color}-500 mb-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            ${LANG.rec_title}
        </h4>
        <p class="text-sm text-${color}-800 dark:text-${color}-300 mb-5 leading-relaxed">${recommendation}</p>
        
        <div class="flex flex-wrap gap-3">
          <button onclick="showStudentData('${subject.name}')" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-${color}-300 dark:border-${color}-700 text-${color}-700 dark:text-${color}-400 font-bold rounded-xl hover:bg-${color}-100 dark:hover:bg-slate-700 transition-colors shadow-sm text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> ${LANG.btn_view_spread}
          </button>
          ${(subject.status !== 'Aman' && subject.status !== 'Belum Dinilai') ? `
            <button onclick="openRemediModal('${subject.name}', ${subject.average})" class="px-5 py-2.5 bg-${color}-600 text-white font-bold rounded-xl hover:bg-${color}-700 transition-colors shadow-md text-sm flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg> ${LANG.btn_make_remedy}
            </button>` : ''}
        </div>
      </div>
    </div>
  `;
}

function renderAnalisisTren(data) {
  let pos = '', neg = '';
  data.forEach(s => {
    if (s.status === 'Aman') {
      pos += `<div class="flex items-start gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100"><span class="text-xl mt-0.5">${s.icon}</span><div><p class="font-bold text-emerald-900">${s.name}</p><p class="text-xs text-emerald-700 font-medium">${LANG.trend_safe_lbl.replace('{avg}', s.average)}</p></div></div>`;
    } else if(s.status !== 'Belum Dinilai') {
      neg += `<div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg border border-red-100"><span class="text-xl mt-0.5">${s.icon}</span><div><p class="font-bold text-red-900">${s.name}</p><p class="text-xs text-red-700 font-medium">${LANG.trend_warn_lbl.replace('{avg}', s.average)}</p></div></div>`;
    }
  });

  const cPos = document.getElementById('trenPositifContainer');
  const cNeg = document.getElementById('trenNegatifContainer');
  
  if(cPos) cPos.innerHTML = pos || `<p class="text-sm text-gray-500 italic">${LANG.trend_no_safe}</p>`;
  if(cNeg) cNeg.innerHTML = neg || `<p class="text-sm text-gray-500 italic">${LANG.trend_no_warn}</p>`;
}

// =================== 3. FUNGSI NAVIGASI & MODAL ===================
function switchTab(tabName, btnElement) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('.tab-active').forEach(btn => {
      btn.classList.remove('tab-active', 'border-tema', 'text-tema');
      btn.classList.add('tab-inactive', 'border-transparent', 'text-gray-500');
  });

  document.getElementById('tab-' + tabName).classList.remove('hidden');
  if (btnElement) {
      btnElement.classList.remove('tab-inactive', 'border-transparent', 'text-gray-500');
      btnElement.classList.add('tab-active', 'border-tema', 'text-tema');
  }
}

function setViewMode(mode) {
    currentViewMode = mode;
    const btnGrid = document.getElementById('viewGrid');
    const btnList = document.getElementById('viewList');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    if (!btnGrid || !btnList || !gridView || !listView) return;

    if (mode === 'grid') {
        btnGrid.classList.add('border-tema', 'bg-tema-light', 'text-tema');
        btnGrid.classList.remove('border-gray-300', 'text-gray-700');
        btnList.classList.remove('border-tema', 'bg-tema-light', 'text-tema');
        btnList.classList.add('border-gray-300', 'text-gray-700');
        gridView.classList.remove('hidden');
        listView.classList.add('hidden');
    } else {
        btnList.classList.add('border-tema', 'bg-tema-light', 'text-tema');
        btnList.classList.remove('border-gray-300', 'text-gray-700');
        btnGrid.classList.remove('border-tema', 'bg-tema-light', 'text-tema');
        btnGrid.classList.add('border-gray-300', 'text-gray-700');
        listView.classList.remove('hidden');
        gridView.classList.add('hidden');
    }
}

function jumpToDetail(subjectName) {
    const detailTabBtn = document.querySelectorAll('[onclick*="switchTab"]')[1];
    switchTab('detail', detailTabBtn);
    showSubjectDetail(subjectName);
    document.getElementById('tab-detail').scrollIntoView({ behavior: 'smooth' });
}

function showStudentData(subjectName) {
  const modal = document.getElementById('studentModal');
  document.getElementById('modalTitle').textContent = LANG.modal_student_title;
  document.getElementById('modalSubtitle').textContent = LANG.modal_student_sub.replace('{name}', subjectName);
  const tableBody = document.getElementById('studentTableBody');
  tableBody.innerHTML = '';

  studentsData.forEach((st, idx) => {
    const nilai = st[subjectName] !== undefined ? st[subjectName] : '-';
    let statusColor = nilai === '-' ? 'slate' : (nilai < 60 ? 'red' : (nilai < 75 ? 'amber' : 'emerald'));
    let statusText = nilai === '-' ? LANG.lbl_unrated : (nilai < 60 ? LANG.lbl_critical : (nilai < 75 ? LANG.lbl_warning : LANG.lbl_safe));

    tableBody.innerHTML += `
      <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group border-b border-gray-100 dark:border-slate-700">
        <td class="px-6 py-4 text-sm text-gray-500 dark:text-slate-400 font-medium">${idx + 1}</td>
        <td class="px-6 py-4 font-bold text-gray-800 dark:text-slate-200 group-hover:text-tema transition-colors">${st.name}</td>
        <td class="px-6 py-4 text-center"><span class="font-black text-lg text-${statusColor}-600 dark:text-${statusColor}-400 bg-${statusColor}-50 dark:bg-${statusColor}-900/20 px-3 py-1 rounded-lg border border-${statusColor}-100 dark:border-${statusColor}-800/50">${nilai}</span></td>
        <td class="px-6 py-4 text-center"><span class="px-3 py-1 bg-${statusColor}-50 dark:bg-${statusColor}-900/20 text-${statusColor}-600 dark:text-${statusColor}-400 text-[10px] rounded-md font-extrabold uppercase tracking-wider border border-${statusColor}-200 dark:border-${statusColor}-800/50 shadow-sm">${statusText}</span></td>
        <td class="px-6 py-4 text-center">
            <button onclick="showStudentProfilePopup(${st.id})" class="text-tema text-[10px] font-bold px-3 py-1.5 rounded-lg border border-tema hover:bg-tema hover:text-white transition-all shadow-sm inline-block whitespace-nowrap">
                ${LANG.lbl_detail}
            </button>
        </td>
      </tr>
    `;
  });
  
  // Update Export Action
  const exportBtn = document.getElementById('exportBtn');
  if(exportBtn) {
      exportBtn.onclick = () => exportStudentGrades(subjectName);
  }
  
  openGenericModal('studentModal'); 
}

function showStudentProfilePopup(studentId) {
    const student = studentsData.find(s => s.id == studentId);
    if (!student) return;

    document.getElementById('profileSiswaName').textContent = student.name;
    const body = document.getElementById('profileSiswaBody');
    body.innerHTML = '';

    // Ambil daftar mapel dari array subjectsData agar urutan konsisten
    subjectsData.forEach(sub => {
        const nilai = student[sub.name] !== undefined ? student[sub.name] : '-';
        const color = nilai === '-' ? 'slate' : (nilai < 60 ? 'red' : (nilai < 75 ? 'amber' : 'emerald'));
        const status = nilai === '-' ? LANG.lbl_unrated : (nilai < 60 ? LANG.lbl_critical : (nilai < 75 ? LANG.lbl_warning : LANG.lbl_safe));

        body.innerHTML += `
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">${sub.icon}</span>
                        <span class="font-bold text-gray-700 dark:text-slate-300 text-sm">${sub.name}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-center font-black text-gray-800 dark:text-white">${nilai}</td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2 py-0.5 bg-${color}-50 dark:bg-${color}-900/20 text-${color}-600 dark:text-${color}-400 text-[9px] font-black uppercase rounded border border-${color}-100 dark:border-${color}-800/50">${status}</span>
                </td>
            </tr>
        `;
    });

    openGenericModal('profileSiswaModal');
}

function closeProfileSiswaModal() {
    const modal = document.getElementById('profileSiswaModal');
    const content = modal.querySelector('.modal-content');
    if(content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
    }
    setTimeout(() => { modal.classList.add('hidden'); }, 300);
}

function exportStudentGrades(subjectName) {
    const fileName = `Sebaran_Nilai_${subjectName.replace(/\s+/g, '_')}_${new Date().toISOString().slice(0,10)}.csv`;
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "No,Nama Siswa,Nilai,Status\n";

    studentsData.forEach((st, idx) => {
        const nilai = st[subjectName] !== undefined ? st[subjectName] : '-';
        let statusText = nilai === '-' ? LANG.lbl_unrated : (nilai < 60 ? LANG.lbl_critical : (nilai < 75 ? LANG.lbl_warning : LANG.lbl_safe));
        
        // Sanitize name for CSV
        const name = `"${st.name.replace(/"/g, '""')}"`;
        csvContent += `${idx + 1},${name},${nilai},"${statusText}"\n`;
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", fileName);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function openGenericModal(modalId) {
    const modal = document.getElementById(modalId);
    if(!modal) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    const content = modal.querySelector('.modal-content');
    
    setTimeout(() => {
        if(content) {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }
    }, 10);
}

function closeStudentModal() {
    const modal = document.getElementById('studentModal');
    const content = modal.querySelector('.modal-content');
    if(content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
    }
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
}

function openRemediModal(subjectName, averageScore) {
  document.getElementById('remediTitle').textContent = LANG.btn_make_remedy;
  document.getElementById('remediSubtitle').textContent = `Mapel: ${subjectName} | Rata-rata Saat Ini: ${averageScore}`;
  document.getElementById('programName').value = `${LANG.remedi_prog_prefix} ${subjectName}`;
  
  const list = document.getElementById('remediStudentList');
  list.innerHTML = '';
  
  const critical = studentsData.filter(s => s[subjectName] !== undefined && s[subjectName] < 75);

  if (critical.length === 0) {
    list.innerHTML = `<p class="text-sm text-emerald-600 bg-emerald-50 p-3 rounded-lg font-bold border border-emerald-100">${LANG.remedi_no_student}</p>`;
  } else {
    critical.forEach(s => {
      const score = s[subjectName];
      const color = score < 60 ? 'red' : 'amber';
      const status = score < 60 ? LANG.lbl_critical : LANG.lbl_warning;
      
      list.innerHTML += `
        <div class="flex items-center justify-between p-2.5 bg-white rounded-lg border border-${color}-200 shadow-sm mb-2">
          <div class="flex items-center gap-3">
            <input type="checkbox" checked class="w-4 h-4 text-orange-600 rounded cursor-pointer">
            <div>
              <p class="text-sm font-bold text-gray-800">${s.name}</p>
              <p class="text-[10px] text-gray-500 font-medium">${LANG.remedi_final_score}: <span class="text-${color}-600 font-bold">${score}</span></p>
            </div>
          </div>
          <span class="px-2 py-1 bg-${color}-50 text-${color}-700 text-[10px] rounded-full font-bold uppercase">${status}</span>
        </div>
      `;
    });
  }

  openGenericModal('remediModal');
}

function closeRemediModal() {
    const modal = document.getElementById('remediModal');
    const content = modal.querySelector('.modal-content');
    if(content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
    }
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 300);
}

function submitRemediProgram(e) {
  if (e) e.preventDefault();
  alert(LANG.remedi_succ_msg);
  closeRemediModal();
}