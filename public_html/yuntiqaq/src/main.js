// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

Vue.config.productionTip = false


Vue.prototype.$axios = axios;
import global from './components/apicom/apicom'
axios.defaults.withCredentials = true; //让ajax携带cookie
import axios from 'axios';
Vue.prototype.api = global.apicom // 绑定原型链的公共api

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);




/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})