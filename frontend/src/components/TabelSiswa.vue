<template>
  <div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4 text-gray-700">Data Siswa (Integrasi Vue + CI4)</h2>

    <div v-if="loading" class="text-center py-4 text-blue-500 font-semibold animate-pulse">
      Sedang mengambil data dari database...
    </div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full border-collapse border border-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="border p-3 text-left text-sm font-semibold text-gray-600">No</th>
            <th class="border p-3 text-left text-sm font-semibold text-gray-600">NIS</th>
            <th class="border p-3 text-left text-sm font-semibold text-gray-600">Nama Lengkap</th>
            <th class="border p-3 text-left text-sm font-semibold text-gray-600">Kelas</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(siswa, index) in dataSiswa" :key="siswa.id" class="hover:bg-gray-50 transition">
            <td class="border p-3 text-sm text-gray-700">{{ index + 1 }}</td>
            <td class="border p-3 text-sm text-gray-700 font-mono">{{ siswa.nis || '-' }}</td>
            <td class="border p-3 text-sm text-gray-700 font-bold">{{ siswa.nama_lengkap }}</td>
            <td class="border p-3 text-sm text-gray-700">{{ siswa.kelas || 'Belum diisi' }}</td>
          </tr>
          
          <tr v-if="dataSiswa.length === 0">
            <td colspan="4" class="p-6 text-center text-gray-400 italic">
              Belum ada data siswa di database.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// State untuk data dan loading
const dataSiswa = ref([]);
const loading = ref(true);

// Fungsi Ambil Data
const fetchData = async () => {
  try {
    // URL ini mengarah ke Controller CI4 yang kita buat tadi
    // Pastikan port 8080 sesuai dengan 'php spark serve' kamu
    const response = await fetch('http://localhost:8080/api/siswa');
    const result = await response.json();

    // Debugging: Cek isi data di Console Browser (F12)
    console.log("Data dari CI4:", result);

    if (result.status === 200) {
      dataSiswa.value = result.data;
    }
  } catch (error) {
    console.error("Gagal koneksi ke Backend:", error);
    alert("Gagal mengambil data! Pastikan 'php spark serve' sudah jalan.");
  } finally {
    loading.value = false;
  }
};

// Jalankan saat halaman dibuka
onMounted(() => {
  fetchData();
});
</script>