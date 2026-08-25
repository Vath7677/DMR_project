import './assets/main.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router' 

const setFavicon = () => {
  const link = (document.querySelector("link[rel*='icon']") as HTMLLinkElement) || document.createElement('link')
  link.type = 'image/png'
  link.rel = 'icon'
  link.href = '/hospital-logo.png?v=' + Date.now()
  document.head.appendChild(link)
}
setFavicon()

const app = createApp(App)
app.use(router) 
app.mount('#app')