import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import LoginView from '../views/LoginView.vue';
import DashboardView from '../views/DashboardView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { guest: true } // Hanya untuk tamu
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView,
      meta: { requiresAuth: true } // Butuh login
    },
    // ... route lainnya
  ]
});

// Navigation Guard (Satpam)
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  // 1. Jika rute butuh login & user belum login -> Tendang ke login
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login');
  } 
  // 2. Jika user sudah login & coba buka halaman login -> Tendang ke dashboard
  else if (to.meta.guest && authStore.isAuthenticated) {
    next('/dashboard');
  } 
  else {
    next();
  }
});

export default router;