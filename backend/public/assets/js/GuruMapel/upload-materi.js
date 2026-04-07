const LANG = window.LANG;

let uploadedFile = null; 
let materials = [];
let currentFilter = 'all';

document.addEventListener("DOMContentLoaded", function () {
  fetchMaterials();
  const today = new Date().toISOString().split("T")[0];
  document.getElementById("publishDate").value = today;
});

function publishDraft(id) {
    if(!confirm(LANG.pub_confirm)) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append('status', 'published');
    formData.append(csrfTokenName, csrfTokenHash);

    fetch(URL_UPDATE_STATUS, {
        method: 'POST',
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            showToast(LANG.succ_pub, "success");
            fetchMaterials(); 
        } else {
            showToast(LANG.err_pub, "error");
        }
    })
    .catch(err => {
        console.error(err);
        showToast(LANG.err_server, "error");
    });
}

function fetchMaterials() {
    fetch(`${URL_GET_DATA}?mapel_id=${ACTIVE_MAPEL_ID}`, {
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            materials = data.data;
            let countText = LANG.material_count.replace('{0}', materials.length);
            document.getElementById("materialCount").textContent = countText;
            renderMaterialsTable();
        }
    })
    .catch(err => console.error("Error fetching materials", err));
}

// ... KODE LAINNYA DI ATAS TETAP SAMA ...

function renderMaterialsTable() {
  const container = document.getElementById("materialsTableContainer");
  const emptyState = document.getElementById("emptyState");

  const filteredMaterials = materials.filter(m => {
      if (currentFilter === 'all') return true;
      return m.status === currentFilter;
  });

  if (filteredMaterials.length === 0) {
    container.style.display = "none";
    emptyState.style.display = "block";
    return;
  }

  container.style.display = "block";
  emptyState.style.display = "none";

  const typeIcons = {
    pdf: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
    ppt: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
    video: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>',
    audio: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>',
    link: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>',
  };

  const typeColors = {
      pdf: 'bg-red-50 text-red-600 border-red-200 dark:!bg-red-900/30 dark:!text-red-400 dark:!border-red-800/50',
      ppt: 'bg-orange-50 text-orange-600 border-orange-200 dark:!bg-orange-900/30 dark:!text-orange-400 dark:!border-orange-800/50',
      video: 'bg-blue-50 text-blue-600 border-blue-200 dark:!bg-blue-900/30 dark:!text-blue-400 dark:!border-blue-800/50',
      audio: 'bg-purple-50 text-purple-600 border-purple-200 dark:!bg-purple-900/30 dark:!text-purple-400 dark:!border-purple-800/50',
      link: 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:!bg-emerald-900/30 dark:!text-emerald-400 dark:!border-emerald-800/50',
  }

  container.innerHTML = `
        <div class="table-container rounded-xl overflow-hidden border border-gray-200 dark:!border-slate-700 shadow-sm">
          <table class="w-full text-left">
            <thead style="background-color: ${THEME_COLOR}">
              <tr class="text-[11px] font-black text-white uppercase tracking-widest">
                <th class="py-4 px-5">Judul Materi</th>
                <th class="py-4 px-5 text-center">Jenis</th>
                <th class="py-4 px-5">Kelas (Rombel)</th>
                <th class="py-4 px-5">Tanggal</th>
                <th class="py-4 px-5 text-center">Status</th>
                <th class="py-4 px-5 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:!divide-slate-700 bg-white dark:!bg-slate-800">
              ${filteredMaterials.map(m => {
                  
                  let btnPublishHtml = '';
                  if (m.status === 'draft') {
                      btnPublishHtml = `
                        <button class="action-button flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 dark:!bg-emerald-900/30 dark:!text-emerald-400 hover:bg-emerald-100 dark:hover:!bg-emerald-900/50 transition-colors" onclick="publishDraft(${m.id})" title="${LANG.btn_pub_now}">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                      `;
                  }
                  
                  let btnDownloadHtml = m.file_path ? `
                      <a href="${URL_ASSETS}${m.file_path}" target="_blank" class="action-button flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 dark:!bg-blue-900/30 dark:!text-blue-400 hover:bg-blue-100 dark:hover:!bg-blue-900/50 transition-colors" title="${LANG.btn_download}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                      </a>
                  ` : '';

                  // IMPLEMENTASI RBAC: Tombol Delete Hanya Muncul Jika Diizinkan
                  let btnDeleteHtml = '';
                  if (typeof CAN_DELETE !== 'undefined' && CAN_DELETE) {
                      btnDeleteHtml = `
                      <button class="action-button flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 dark:!bg-red-900/30 dark:!text-red-400 hover:bg-red-100 dark:hover:!bg-red-900/50 transition-colors outline-none" onclick="deleteMaterial(${m.id})" title="${LANG.btn_delete}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                      `;
                  }

                  let statusBadge = m.status === 'published' 
                    ? `<span class="inline-flex px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 dark:!bg-blue-900/30 dark:!text-blue-400 dark:!border-blue-800/50 transition-colors">${LANG.status_pub}</span>`
                    : `<span class="inline-flex px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-700 border border-gray-200 dark:!bg-slate-700 dark:!text-slate-300 dark:!border-slate-600 transition-colors">${LANG.status_draft}</span>`;

                  return `
                <tr class="hover:bg-gray-50 dark:hover:!bg-slate-700/50 transition-colors">
                  <td class="py-4 px-5">
                    <p class="font-bold text-gray-900 dark:!text-white text-sm transition-colors">${m.judul}</p>
                  </td>
                  <td class="py-4 px-5 text-center">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border transition-colors ${typeColors[m.jenis] || typeColors['link']}">
                      ${typeIcons[m.jenis] || typeIcons['link']}
                      ${m.jenis.toUpperCase()}
                    </span>
                  </td>
                  <td class="py-4 px-5">
                    <p class="font-bold text-xs text-gray-700 dark:!text-slate-300 transition-colors">${m.classes.join(", ")}</p>
                  </td>
                  <td class="py-4 px-5">
                    <p class="font-bold text-[10px] uppercase tracking-widest text-gray-500 dark:!text-slate-400 transition-colors">${formatDate(m.tanggal_publikasi)}</p>
                  </td>
                  <td class="py-4 px-5 text-center">
                    ${statusBadge}
                  </td>
                  <td class="py-4 px-5">
                    <div class="flex gap-2 justify-center">
                      ${btnPublishHtml}
                      ${btnDownloadHtml}
                      ${btnDeleteHtml}
                    </div>
                  </td>
                </tr>
              `}).join("")}
            </tbody>
          </table>
        </div>
      `;
}

// ... KODE LAINNYA DI BAWAH TETAP SAMA ...

function filterMaterials(button, filter) {
  document.querySelectorAll(".filter-button").forEach((btn) => btn.classList.remove("active"));
  button.classList.add("active");
  currentFilter = filter;
  renderMaterialsTable();
}

function handleDragOver(e) {
  e.preventDefault(); e.stopPropagation();
  document.getElementById("dropzone").classList.add("opacity-50", "border-[var(--warna-primary)]");
}

function handleDragLeave(e) {
  e.preventDefault(); e.stopPropagation();
  document.getElementById("dropzone").classList.remove("opacity-50", "border-[var(--warna-primary)]");
}

function handleDrop(e) {
  e.preventDefault(); e.stopPropagation();
  document.getElementById("dropzone").classList.remove("opacity-50", "border-[var(--warna-primary)]");
  const files = e.dataTransfer.files;
  if (files.length > 0) addFiles(files[0]); 
}

function handleFileSelect(e) {
  const files = e.target.files;
  if (files.length > 0) addFiles(files[0]);
}

function addFiles(file) {
  if (file.size > 10 * 1024 * 1024) {
    showToast(LANG.err_size, "error");
    return;
  }

  uploadedFile = file; 
  const fileList = document.getElementById("fileList");

  // Tambahkan kelas Tailwind UI agar di Dark Mode tidak putih silau
  fileList.innerHTML = `
      <div class="file-item bg-emerald-50 dark:!bg-emerald-900/20 border border-emerald-200 dark:!border-emerald-800/50 p-3 rounded-xl flex items-center gap-4 transition-colors">
        <div class="bg-emerald-100 dark:!bg-emerald-900/50 p-2 rounded-lg">
          <svg class="w-6 h-6 text-emerald-600 dark:!text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <p class="font-bold text-emerald-900 dark:!text-emerald-300 text-sm mb-0.5 truncate transition-colors">${file.name}</p>
          <p class="text-xs text-emerald-600 dark:!text-emerald-500 font-bold uppercase tracking-widest transition-colors">${formatFileSize(file.size)}</p>
        </div>
        <button type="button" onclick="removeFile()" class="text-rose-600 dark:!text-rose-400 hover:bg-rose-200 dark:hover:!bg-rose-900/50 bg-rose-100 dark:!bg-rose-900/30 p-2.5 rounded-lg transition-colors outline-none">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
  `;
}

function removeFile() {
  uploadedFile = null;
  document.getElementById("fileList").innerHTML = "";
  document.getElementById("fileInput").value = "";
}

function formatFileSize(bytes) {
  if (bytes < 1024) return bytes + " B";
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + " KB";
  return (bytes / (1024 * 1024)).toFixed(1) + " MB";
}

function toggleMultiselect() {
  const options = document.getElementById("multiselectOptions");
  options.classList.toggle("hidden");
}

function updateSelectedClasses() {
  const checkboxes = document.querySelectorAll('#multiselectOptions input[type="checkbox"]:checked');
  const tagsContainer = document.getElementById("selectedClassesTags");
  const textSpan = document.getElementById("selectedClassesText");

  tagsContainer.innerHTML = "";

  if (checkboxes.length === 0) {
    textSpan.textContent = LANG.sel_class;
    // Tambahkan warna teks default saat tidak ada yang dipilih
    textSpan.className = "text-gray-500 dark:!text-slate-400";
  } else {
    textSpan.textContent = `${checkboxes.length} ${LANG.class_selected}`;
    // Ganti warna teks saat ada yang dipilih
    textSpan.className = "text-gray-900 dark:!text-white font-bold";
    
    checkboxes.forEach((checkbox) => {
      const className = checkbox.getAttribute('data-name');
      const tag = document.createElement("div");
      // Badge Kelas Terpilih
      tag.className = "class-tag bg-blue-50 text-blue-700 border border-blue-200 dark:!bg-blue-900/30 dark:!text-blue-400 dark:!border-blue-800/50 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider inline-flex items-center gap-1.5 transition-colors shadow-sm";
      tag.innerHTML = `
            <span>${className}</span>
            <button type="button" onclick="removeClassTag('${checkbox.value}')" class="hover:text-red-500 transition-colors outline-none">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          `;
      tagsContainer.appendChild(tag);
    });
  }
}

function removeClassTag(value) {
  const checkbox = document.querySelector(`#multiselectOptions input[value="${value}"]`);
  if (checkbox) {
    checkbox.checked = false;
    updateSelectedClasses();
  }
}

function submitMateri(e) {
  e.preventDefault(); 

  const title = document.getElementById("materiTitle").value.trim();
  const type = document.getElementById("materiType").value;
  const desc = document.getElementById("materiDescription").value.trim();
  const date = document.getElementById("publishDate").value;
  
  const statusRadio = document.querySelector('input[name="status"]:checked');
  const status = statusRadio ? statusRadio.value : 'published';

  const selectedClasses = Array.from(document.querySelectorAll("#multiselectOptions input:checked")).map(cb => cb.value);

  if (!title || !type) {
    showToast(LANG.err_req, "error");
    return;
  }
  if (selectedClasses.length === 0) {
    showToast(LANG.err_no_class, "error");
    return;
  }
  if (type !== 'link' && !uploadedFile) {
    showToast(LANG.err_no_file, "error");
    return;
  }

  const formData = new FormData();
  formData.append('mapel_id', ACTIVE_MAPEL_ID);
  formData.append('judul', title);
  formData.append('jenis', type);
  formData.append('deskripsi', desc);
  formData.append('rombel_ids', JSON.stringify(selectedClasses)); 
  formData.append('tanggal', date);
  formData.append('status', status);
  if (uploadedFile) {
      formData.append('file_materi', uploadedFile); 
  }
  formData.append(csrfTokenName, csrfTokenHash);

  const button = document.getElementById("publishButton");
  const originalText = button.innerHTML;
  button.disabled = true;
  button.innerHTML = `<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.uploading}`;

  fetch(URL_STORE, {
      method: 'POST',
      body: formData,
      headers: { "X-Requested-With": "XMLHttpRequest" } 
  })
  .then(res => res.json())
  .then(data => {
      if(data.status === 'success') {
          showToast(LANG.succ_upload, "success");
          document.getElementById("uploadForm").reset();
          removeFile();
          
          // Reset Checkbox & Tags
          document.querySelectorAll('#multiselectOptions input[type="checkbox"]').forEach(c => c.checked = false);
          updateSelectedClasses();
          
          fetchMaterials(); 
      } else {
          showToast(LANG.err_upload, "error");
      }
  })
  .catch(err => {
      console.error(err);
      showToast(LANG.err_conn, "error");
  })
  .finally(() => {
      button.disabled = false;
      button.innerHTML = originalText;
  });
}

function deleteMaterial(id) {
    if(!confirm(LANG.del_confirm)) return;

    const formData = new FormData();
    formData.append('id', id);
    formData.append(csrfTokenName, csrfTokenHash);

    fetch(URL_DELETE, {
        method: 'POST',
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            showToast(LANG.succ_del, "success");
            fetchMaterials(); 
        }
    });
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return `${date.getDate()} ${LANG.months[date.getMonth()]} ${date.getFullYear()}`;
}

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