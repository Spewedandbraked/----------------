import './assets/mainv2.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './Appv2.vue'

const app = createApp(App)
app.use(createPinia())
app.mount('#modulus')

