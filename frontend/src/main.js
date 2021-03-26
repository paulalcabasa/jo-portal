import Vue from 'vue'
import { ToastPlugin, ModalPlugin } from 'bootstrap-vue'
import VueCompositionAPI from '@vue/composition-api'

import router from './router'
import store from './store'
import App from './App.vue'
import axios from 'axios'
import VueAxios from "vue-axios";
Vue.use(VueAxios, axios);
Vue.axios.defaults.baseURL = process.env.VUE_APP_API_URL;
// Global Components
import './global-components'

// 3rd party plugins
import '@/libs/portal-vue'
import '@/libs/toastification'
import '@/libs/vue-select'
// BSV Plugin Registration
Vue.use(ToastPlugin)
Vue.use(ModalPlugin)

// Composition API
Vue.use(VueCompositionAPI)

// import core styles
require('@core/scss/core.scss')

// import assets styles
require('@/assets/scss/style.scss')
require('@core/assets/fonts/feather/iconfont.css') // For form-wizard
import useJwt from "@/auth/jwt/useJwt";


// pass token to all request
axios.interceptors.request.use(function (config) {
  const token = useJwt.getToken();
  config.headers.Authorization = "Bearer " + token;
 
  return config;
}, function (error) {
  // Do something with request error
  return Promise.reject(error);
});

import createAuthRefreshInterceptor from 'axios-auth-refresh';

const refreshAuthLogic = failedRequest => axios.post('api/auth/refresh').then(tokenRefreshResponse => {
    useJwt.setToken(tokenRefreshResponse.data.access_token);
	console.log("got here");
    failedRequest.response.config.headers['Authorization'] = 'Bearer ' + tokenRefreshResponse.data.access_token;
    return Promise.resolve();
});

createAuthRefreshInterceptor(axios, refreshAuthLogic);


 // Ensure we checked auth before each page load.
router.beforeEach((to, from, next) => {
  const dontVerifyRoutes = ['login'];
	if(!dontVerifyRoutes.includes(to.name)) {
		axios.post('api/auth/me').then(response => {
			const userData  = response.data.user;
			localStorage.setItem("userData",JSON.stringify(userData));
			next()
		}).catch(err => {
			useJwt.destroyToken();
			useJwt.destroyUser();
			next({ name: 'login' });
		});
		next()	
	} 
	else {
		next();
	}
});

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app')
