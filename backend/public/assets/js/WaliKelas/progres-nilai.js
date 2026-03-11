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
      document.getElementById('chartContainer').innerHTML = '<p class="text-gray-400 w-full text-center pb-10">Belum ada data nilai.</p>';
      document.getElementById('gridView').innerHTML = '<p class="text-gray-400 w-full text-center pb-10">Belum ada data detail.</p>';
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
    select.innerHTML = '<option value="">Semua Mapel</option>';
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
        document.getElementById('chartContainer').innerHTML = '<p class="text-gray-400 w-full text-center pb-10">Data tidak ditemukan.</p>';
        document.getElementById('gridView').innerHTML = '<p class="text-gray-400 w-full text-center py-6 col-span-3">Data tidak ditemukan.</p>';
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" class="text-center py-6 text-gray-500">Data tidak ditemukan.</td></tr>';
    }
}

// =================== 2. RENDER TAB BAWAH ===================
function renderGrid(data) {
  const gridView = document.getElementById('gridView');
  const tableBody = document.getElementById('tableBody');
  if(!gridView || !tableBody) return;
  
  gridView.innerHTML = ''; tableBody.innerHTML = '';

  data.forEach((subject) => {
    const statusColor = subject.status === 'Aman' ? 'emerald' : (subject.status === 'Rawan' ? 'amber' : 'red');
    const trendIcon = subject.trend === 'up' ? '📈' : (subject.trend === 'down' ? '📉' : '➡️');
    const trendText = subject.trend === 'up' ? 'Meningkat' : (subject.trend === 'down' ? 'Menurun' : 'Stabil');

    const card = document.createElement('div');
    card.className = 'bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all cursor-pointer group';
    card.onclick = () => jumpToDetail(subject.name);
    card.innerHTML = `
      <div class="flex items-start justify-between mb-4">
        <div>
          <div class="text-3xl mb-2 transform group-hover:scale-110 transition-transform">${subject.icon}</div>
          <h3 class="font-bold text-gray-800 text-lg truncate pr-2" title="${subject.name}">${subject.name}</h3>
        </div>
        <span class="px-2.5 py-1 bg-${statusColor}-100 text-${statusColor}-700 text-[10px] font-bold uppercase rounded-full tracking-wider">${subject.status}</span>
      </div>
      <div class="grid grid-cols-3 gap-2 mb-4 pb-4 border-b border-gray-100">
        <div><p class="text-[10px] text-gray-500 uppercase font-semibold">Rata-rata</p><p class="text-xl font-black text-gray-800">${subject.average}</p></div>
        <div><p class="text-[10px] text-gray-500 uppercase font-semibold">Tertinggi</p><p class="text-xl font-black text-emerald-600">${subject.highest}</p></div>
        <div><p class="text-[10px] text-gray-500 uppercase font-semibold">Terendah</p><p class="text-xl font-black text-red-600">${subject.lowest}</p></div>
      </div>
      <div class="progress-bar h-2 bg-gray-100 rounded-full overflow-hidden mb-4">
        <div class="progress-fill h-full rounded-full transition-all duration-1000" style="width: ${subject.average}%; background: ${subject.color};"></div>
      </div>
      <p class="text-xs text-center text-gray-500 group-hover:text-[var(--warna-primary)] font-medium transition-colors">Klik untuk lihat detail →</p>
    `;
    gridView.appendChild(card);

    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50 transition-colors cursor-pointer';
    row.onclick = card.onclick;
    row.innerHTML = `
      <td class="px-6 py-4">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center text-lg shadow-sm border border-gray-100" style="background-color: ${subject.color}15">${subject.icon}</div>
          <p class="font-semibold text-gray-800">${subject.name}</p>
        </div>
      </td>
      <td class="px-6 py-4 text-center"><span class="font-bold text-lg text-gray-800">${subject.average}</span></td>
      <td class="px-6 py-4 text-center"><span class="font-semibold text-emerald-600">${subject.highest}</span></td>
      <td class="px-6 py-4 text-center"><span class="font-semibold text-red-600">${subject.lowest}</span></td>
      <td class="px-6 py-4 text-center"><span class="text-xl" title="${trendText}">${trendIcon}</span></td>
      <td class="px-6 py-4 text-center"><span class="inline-block px-2.5 py-1 bg-${statusColor}-100 text-${statusColor}-700 text-xs rounded-full font-bold uppercase tracking-wider">${subject.status}</span></td>
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
  const color = subject.status === 'Aman' ? 'emerald' : (subject.status === 'Rawan' ? 'amber' : 'red');

  let recommendation = '';
  if (subject.status === 'Aman') recommendation = `Nilai <b>${subject.name}</b> sangat aman (${subject.average}). Tidak ada siswa kritis. Pertahankan metode ajar saat ini.`;
  else if (subject.status === 'Rawan') recommendation = `Rata-rata <b>${subject.name}</b> di ambang batas (${subject.average}). Perlu peninjauan metode ajar dan penugasan kelompok.`;
  else recommendation = `Peringatan! Nilai <b>${subject.name}</b> sangat kritis (${subject.average}). Wajib segera buat program remedi terstruktur untuk siswa yang nilainya di bawah KKM.`;

  panel.innerHTML = `
    <div class="animate-fade-in">
      <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-gray-100" style="background-color: ${subject.color}15">
            ${subject.icon}
          </div>
          <div>
            <h3 class="text-2xl font-bold text-gray-800">${subject.name}</h3>
            <p class="text-sm text-gray-500 font-medium">Analisis Detail Capaian Nilai</p>
          </div>
        </div>
        <span class="px-4 py-1.5 bg-${color}-100 text-${color}-700 rounded-full font-bold text-sm tracking-wider uppercase border border-${color}-200 shadow-sm">${subject.status}</span>
      </div>

      <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-50 border border-gray-100 p-4 rounded-xl text-center"><p class="text-[10px] text-gray-500 font-bold uppercase mb-1">Rata-rata</p><p class="text-3xl font-black text-gray-800">${subject.average}</p></div>
        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl text-center"><p class="text-[10px] text-emerald-600 font-bold uppercase mb-1">Tertinggi</p><p class="text-3xl font-black text-emerald-600">${subject.highest}</p></div>
        <div class="bg-red-50 border border-red-100 p-4 rounded-xl text-center"><p class="text-[10px] text-red-600 font-bold uppercase mb-1">Terendah</p><p class="text-3xl font-black text-red-600">${subject.lowest}</p></div>
        <div class="bg-purple-50 border border-purple-100 p-4 rounded-xl text-center"><p class="text-[10px] text-purple-600 font-bold uppercase mb-1">Range Jarak</p><p class="text-3xl font-black text-purple-600">${subject.highest - subject.lowest}</p></div>
      </div>

      <div class="bg-${color}-50 border border-${color}-200 rounded-xl p-5 mb-2 shadow-sm">
        <h4 class="font-bold text-${color}-900 mb-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Analisis AI Sistem
        </h4>
        <p class="text-sm text-${color}-800 mb-5 leading-relaxed">${recommendation}</p>
        
        <div class="flex flex-wrap gap-3">
          <button onclick="showStudentData('${subject.name}')" class="px-5 py-2.5 bg-white border border-${color}-300 text-${color}-700 font-bold rounded-xl hover:bg-${color}-100 transition-colors shadow-sm text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> Lihat Sebaran Nilai Siswa
          </button>
          ${subject.status !== 'Aman' ? `
            <button onclick="openRemediModal('${subject.name}', ${subject.average})" class="px-5 py-2.5 bg-${color}-600 text-white font-bold rounded-xl hover:bg-${color}-700 transition-colors shadow-md text-sm flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg> Buat Program Remedi
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
      pos += `<div class="flex items-start gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100"><span class="text-xl mt-0.5">${s.icon}</span><div><p class="font-bold text-emerald-900">${s.name}</p><p class="text-xs text-emerald-700 font-medium">Kondisi Aman (Rata-rata: ${s.average})</p></div></div>`;
    } else {
      neg += `<div class="flex items-start gap-3 p-3 bg-red-50 rounded-lg border border-red-100"><span class="text-xl mt-0.5">${s.icon}</span><div><p class="font-bold text-red-900">${s.name}</p><p class="text-xs text-red-700 font-medium">Perlu Atensi (Rata-rata: ${s.average})</p></div></div>`;
    }
  });

  const cPos = document.getElementById('trenPositifContainer');
  const cNeg = document.getElementById('trenNegatifContainer');
  
  if(cPos) cPos.innerHTML = pos || '<p class="text-sm text-gray-500 italic">Belum ada mapel dalam kategori aman.</p>';
  if(cNeg) cNeg.innerHTML = neg || '<p class="text-sm text-gray-500 italic">Alhamdulillah, tidak ada mapel rawan/kritis.</p>';
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

// Modal Data Siswa
function showStudentData(subjectName) {
  const modal = document.getElementById('studentModal');
  document.getElementById('modalTitle').textContent = `Sebaran Nilai Siswa`;
  document.getElementById('modalSubtitle').textContent = `Nilai Rapor: ${subjectName}`;
  const tableBody = document.getElementById('studentTableBody');
  tableBody.innerHTML = '';

  studentsData.forEach((st, idx) => {
    const nilai = st[subjectName] !== undefined ? st[subjectName] : '-';
    let statusColor = nilai === '-' ? 'gray' : (nilai < 60 ? 'red' : (nilai < 75 ? 'amber' : 'emerald'));
    let statusText = nilai === '-' ? 'Belum Ada' : (nilai < 60 ? 'Kritis' : (nilai < 75 ? 'Rawan' : 'Aman'));

    tableBody.innerHTML += `
      <tr class="hover:bg-gray-50 border-b border-gray-100">
        <td class="px-4 py-3 text-sm text-gray-500">${idx + 1}</td>
        <td class="px-4 py-3 font-semibold text-gray-800">${st.name}</td>
        <td class="px-4 py-3 text-center font-black text-lg text-${statusColor}-600">${nilai}</td>
        <td class="px-4 py-3 text-center"><span class="px-2.5 py-1 bg-${statusColor}-100 text-${statusColor}-700 text-[10px] rounded-full font-bold uppercase">${statusText}</span></td>
        <td class="px-4 py-3 text-center"><button class="text-tema text-xs font-bold px-2 py-1 rounded border border-tema hover:bg-tema-light transition-colors">Cek</button></td>
      </tr>
    `;
  });
  
  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}
function closeStudentModal() { document.getElementById('studentModal').classList.add('hidden'); document.body.style.overflow = ''; }

// Modal Remedi
function openRemediModal(subjectName, averageScore) {
  document.getElementById('remediTitle').textContent = `Buat Program Remedi`;
  document.getElementById('remediSubtitle').textContent = `Mapel: ${subjectName} | Rata-rata Saat Ini: ${averageScore}`;
  document.getElementById('programName').value = `Program Intensif ${subjectName}`;
  
  const list = document.getElementById('remediStudentList');
  list.innerHTML = '';
  
  const critical = studentsData.filter(s => s[subjectName] !== undefined && s[subjectName] < 75);

  if (critical.length === 0) {
    list.innerHTML = '<p class="text-sm text-emerald-600 bg-emerald-50 p-3 rounded-lg font-bold border border-emerald-100">Tidak ada siswa yang memerlukan remedi.</p>';
  } else {
    critical.forEach(s => {
      const score = s[subjectName];
      const color = score < 60 ? 'red' : 'amber';
      const status = score < 60 ? 'Kritis' : 'Rawan';
      
      list.innerHTML += `
        <div class="flex items-center justify-between p-2.5 bg-white rounded-lg border border-${color}-200 shadow-sm mb-2">
          <div class="flex items-center gap-3">
            <input type="checkbox" checked class="w-4 h-4 text-orange-600 rounded cursor-pointer">
            <div>
              <p class="text-sm font-bold text-gray-800">${s.name}</p>
              <p class="text-[10px] text-gray-500 font-medium">Nilai Akhir: <span class="text-${color}-600 font-bold">${score}</span></p>
            </div>
          </div>
          <span class="px-2 py-1 bg-${color}-50 text-${color}-700 text-[10px] rounded-full font-bold uppercase">${status}</span>
        </div>
      `;
    });
  }

  const modal = document.getElementById('remediModal');
  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}
function closeRemediModal() { document.getElementById('remediModal').classList.add('hidden'); document.body.style.overflow = ''; }
function submitRemediProgram(e) {
  if (e) e.preventDefault();
  alert('Program Remedi berhasil disimpan dan akan dijadwalkan otomatis oleh sistem!');
  closeRemediModal();
}