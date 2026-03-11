import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8080/api', // Sesuaikan dengan port CI4 Anda
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor: Otomatis menyisipkan Token di setiap request
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;