const BASE_URL = document.querySelector('meta[name="base-url"]')?.content || "";

setInterval(() => {
    const now = new Date();
    const el = document.getElementById('realtimeClock');
    if(el) el.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}, 1000);

const hour = new Date().getHours();
let greeting = LANG.greeting_night;
if (hour >= 4 && hour < 11) greeting = LANG.greeting_morning;
else if (hour >= 11 && hour < 15) greeting = LANG.greeting_afternoon;
else if (hour >= 15 && hour < 18) greeting = LANG.greeting_evening;

const elGreeting = document.getElementById('greetingTime');
if(elGreeting) elGreeting.innerText = greeting;

document.addEventListener("DOMContentLoaded", () => {
    setTimeout(() => {
        document.querySelectorAll('.progress-fill').forEach(bar => {
            bar.style.width = bar.getAttribute('data-width');
        });
    }, 200);
});

function exportRekap(btn) {
    const textAsli = btn.innerHTML;
    btn.innerHTML = `<svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span class="hidden md:inline ml-1">${LANG.js_preparing}</span>`;
    btn.disabled = true;

    setTimeout(() => {
        btn.innerHTML = textAsli;
        btn.disabled = false;
        
        const toast = document.getElementById('customToast');
        if(toast){
            toast.classList.remove('translate-y-20', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            
            setTimeout(() => { closeToast(); }, 4000);
        }
        
        window.location.href = BASE_URL + '/tahfidz/dashboard/exportRekap';
    }, 1500); 
}

function closeToast() {
    const toast = document.getElementById('customToast');
    if (toast) {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-20', 'opacity-0');
    }
}

function openSetoranModal() {
    const modal = document.getElementById('modalSetoran');
    const backdrop = document.getElementById('backdropSetoran');
    const card = document.getElementById('cardSetoran');
    
    if(!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        if(backdrop){
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
        }
        if(card){
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }
    }, 10);
}

function closeSetoranModal() {
    const modal = document.getElementById('modalSetoran');
    const backdrop = document.getElementById('backdropSetoran');
    const card = document.getElementById('cardSetoran');
    
    if(!modal) return;

    if(backdrop){
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
    }
    
    if(card){
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function simpanSetoranCepat() {
    const btn = document.getElementById('btnSimpanSetoran');
    if(!btn) return;

    const textAsli = btn.innerHTML;
    btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.js_saving}`;
    btn.disabled = true;

    setTimeout(() => {
        btn.innerHTML = textAsli;
        btn.disabled = false;
        
        closeSetoranModal();
        
        setTimeout(() => {
            openSuccessModal();
        }, 300); 
        
    }, 1500); 
}

function openSuccessModal() {
    const modal = document.getElementById('modalSuccess');
    const backdrop = document.getElementById('backdropSuccess');
    const card = document.getElementById('cardSuccess');
    const checkIcon = document.getElementById('checkIcon');
    
    if(!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        if(backdrop){
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
        }
        if(card){
            card.classList.remove('scale-50', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }
        
        setTimeout(() => {
            if(checkIcon){
                checkIcon.classList.remove('scale-0');
                checkIcon.classList.add('scale-100');
            }
        }, 300);
    }, 10);
}

function closeSuccessModal() {
    const modal = document.getElementById('modalSuccess');
    const backdrop = document.getElementById('backdropSuccess');
    const card = document.getElementById('cardSuccess');
    const checkIcon = document.getElementById('checkIcon');
    
    if(!modal) return;

    if(backdrop){
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
    }
    if(card){
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-50', 'opacity-0');
    }
    if(checkIcon){
        checkIcon.classList.remove('scale-100');
        checkIcon.classList.add('scale-0');
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 500);
}