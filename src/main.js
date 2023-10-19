import Vue from 'vue'
import App from './App.vue'
import router from './router.js'

Vue.mixin({ methods: { t, n } })

export default new Vue({
	el: '#content',
	router,
	render: h => h(App),
})