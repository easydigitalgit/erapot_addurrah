import { defineStore } from 'pinia';
import api from '../services/api';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: JSON.parse(localStorage.getItem('user')) || null,
  }),
  
  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user?.role_id === 1, // Contoh cek role
  },

  actions: {
    async login(credentials) {
      try {
        // Kirim request ke Backend CI4
        const response = await api.post('/login', credentials);
        
        const { token, user } = response.data;

        // Simpan ke State & LocalStorage
        this.token = token;
        this.user = user;
        
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));

        return true; // Login sukses
      } catch (error) {
        throw error.response?.data?.messages?.error || 'Login Gagal';
      }
    },

    logout() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      
      // Redirect paksa (opsional)
      window.location.href = '/login';
    }
  }
});