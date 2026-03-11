// ==========================================
// FILTER DAN PENCARIAN KELAS
// ==========================================

let currentStatusFilter = "all";

function filterByStatus(button, status) {
  document.querySelectorAll(".filter-button").forEach((btn) => {
    btn.classList.remove("active");
    btn.style.backgroundColor = "transparent";
    btn.style.color = "#4B5563"; 
  });
  
  button.classList.add("active");
  const warnaPrimary = getComputedStyle(document.documentElement).getPropertyValue('--warna-scroll').trim() || '#3B82F6';
  button.style.backgroundColor = warnaPrimary;
  button.style.color = "white";

  currentStatusFilter = status;
  applyFilters();
}

function filterClasses() {
  applyFilters();
}

function applyFilters() {
  const searchTerm = document.getElementById("searchInput").value.toLowerCase();
  const cards = document.querySelectorAll(".class-card");
  let visibleCount = 0;

  cards.forEach((card) => {
    const status = card.getAttribute("data-status");
    const searchData = card.getAttribute("data-search");

    const matchesStatus = currentStatusFilter === "all" || status === currentStatusFilter;
    const matchesSearch = searchData.includes(searchTerm);

    if (matchesStatus && matchesSearch) {
      card.style.display = "flex";
      visibleCount++;
    } else {
      card.style.display = "none";
    }
  });

  const emptyState = document.getElementById("emptyState");
  const classGrid = document.getElementById("classGrid");

  if (visibleCount === 0) {
    emptyState.classList.remove("hidden");
    classGrid.style.display = "none";
  } else {
    emptyState.classList.add("hidden");
    classGrid.style.display = "grid";
  }
}

document.addEventListener("DOMContentLoaded", () => {
    const firstButton = document.querySelector(".filter-button");
    if(firstButton) {
        firstButton.classList.add("active");
        const warnaPrimary = getComputedStyle(document.documentElement).getPropertyValue('--warna-scroll').trim() || '#3B82F6';
        firstButton.style.backgroundColor = warnaPrimary;
        firstButton.style.color = "white";
    }
});