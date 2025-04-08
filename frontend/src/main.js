import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import Toast from 'vue-toast-notification'
import Tippy from 'vue-tippy'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'vue-toast-notification/dist/theme-sugar.css'
import 'tippy.js/dist/tippy.css'


const app = createApp(App)
app.use(createPinia())
app.use(router)
app.use(Toast)
app.use(Tippy, {
	directive: 'tippy',
	component: 'Tippy',
	defaultProps: {
		placement: 'top',
		allowHTML: true,
		animation: 'fade'
	}
})
app.mount('#app')
