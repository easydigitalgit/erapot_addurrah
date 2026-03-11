<script setup>
import { ref, reactive } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

// State Form
const form = reactive({
  username: '',
  password: '',
  role: '' // Sesuai dropdown
});

// Utilities
const authStore = useAuthStore();
const router = useRouter();
const isLoading = ref(false);
const errorMessage = ref('');
const showPassword = ref(false);

// Logic Login
const handleLogin = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {
    // Panggil action dari Pinia
    await authStore.login(form);
    
    // Jika sukses, redirect ke dashboard
    router.push('/dashboard');
  } catch (error) {
    errorMessage.value = error; // Tampilkan error dari backend
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="login-wrapper h-screen w-full flex flex-col lg:flex-row overflow-hidden">
    
    <div class="branding-section hidden lg:flex w-full lg:w-1/2 p-12 flex-col justify-center items-center text-white relative bg-emerald-600">
        <h1 class="text-4xl font-bold">Rapor Digital SMPIT</h1>
        <p class="mt-2 text-white/90">Sistem Informasi Akademik Terpadu</p>
    </div>

    <div class="w-full lg:w-1/2 p-6 lg:p-12 flex flex-col justify-center items-center bg-white">
      <div class="card-form w-full max-w-md">
        
        <div class="text-center mb-8">
          <h2 class="text-3xl font-bold text-gray-800 mb-2">Masuk ke Sistem</h2>
          <p class="text-gray-500">Silakan login menggunakan akun resmi sekolah</p>
        </div>

        <div v-if="errorMessage" class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-sm">
          <p class="font-bold">Gagal Masuk</p>
          <p>{{ errorMessage }}</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Username / Email</label>
            <input 
              v-model="form.username" 
              type="text" 
              class="w-full pl-4 pr-4 py-3.5 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none" 
              placeholder="Masukkan username" 
              required
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <div class="relative">
              <input 
                v-model="form.password" 
                :type="showPassword ? 'text' : 'password'"
                class="w-full pl-4 pr-12 py-3.5 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none" 
                placeholder="Masukkan password" 
                required
              >
              <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400">
                <span v-if="!showPassword">👁️</span>
                <span v-else>🚫</span>
              </button>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role Pengguna</label>
            <select v-model="form.role" class="w-full px-4 py-3.5 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-emerald-500 outline-none" required>
              <option value="" disabled>Pilih role Anda</option>
              <option value="admin">👨‍💼 Admin Sekolah</option>
              <option value="guru">👨‍🏫 Guru Mapel</option>
              <option value="wali_kelas">👩‍🏫 Wali Kelas</option>
              <option value="orang_tua">👨‍👩‍👧 Orang Tua</option>
            </select>
          </div>

          <button 
            type="submit" 
            :disabled="isLoading"
            class="w-full py-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center"
          >
            <span v-if="isLoading">Memproses...</span>
            <span v-else>Masuk</span>
          </button>

        </form>

        <div class="mt-8 text-center text-sm text-gray-400">
          &copy; SMPIT Ad Durrah
        </div>
      </div>
    </div>
  </div>
</template>