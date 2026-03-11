<script setup>
// Import komponen yang baru kita buat
import TabelSiswa from './components/TabelSiswa.vue'
</script>

<template>
  <div class="min-h-screen bg-slate-100 py-10 px-4">
    <div class="max-w-4xl mx-auto">
      
      <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-blue-600">Integrasi CI4 + Vue</h1>
        <p class="text-gray-600">Mengambil data dari MySQL melalui API CodeIgniter 4</p>
      </div>

      <TabelSiswa />

    </div>
  </div>
</template>