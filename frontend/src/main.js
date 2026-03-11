import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// --- TAMBAHKAN BARIS INI ---
import './assets/styles/main.css' 
// ---------------------------

const app = createApp(App)
app.use(router)
app.mount('#app')