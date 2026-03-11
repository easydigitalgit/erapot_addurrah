// ==========================================
// SABUK PENGAMAN (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
    months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
};

// =========================================================
// Fungsi UI Interaktif untuk Dashboard Guru
// =========================================================

function openMobileSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("sidebar-overlay");
  if(sidebar && overlay) {
      sidebar.classList.add("mobile-open");
      overlay.classList.add("active");
      document.body.style.overflow = "hidden";
  }
}

function closeMobileSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("sidebar-overlay");
  if(sidebar && overlay) {
      sidebar.classList.remove("mobile-open");
      overlay.classList.remove("active");
      document.body.style.overflow = "";
  }
}

// Fungsi Tanggal Dinamis
function updateDate() {
  const now = new Date();
  const dayName = LANG.days[now.getDay()];
  const date = now.getDate();
  const month = LANG.months[now.getMonth()];
  const year = now.getFullYear();

  const dayEl = document.getElementById("currentDay");
  const dateEl = document.getElementById("currentDate");

  if (dayEl) dayEl.textContent = dayName;
  if (dateEl) dateEl.textContent = `${date} ${month} ${year}`;
}

document.addEventListener("DOMContentLoaded", () => {
    updateDate();
});