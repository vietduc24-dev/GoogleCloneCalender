import './bootstrap'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './app.vue'
import router from './routers'

// Import CSS
import '../css/app.css'

const app = createApp(App)

// Use plugins
app.use(createPinia())
app.use(router)

// Mount app
app.mount('#app')
