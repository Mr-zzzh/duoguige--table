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


axios.interceptors.request.use(function (config) {
  // console.log(localStorage.getItem('admin_info'))
  let token = ''
  if(localStorage.getItem('admin_info') == null){
    return config
  }else{
    token = JSON.parse(localStorage.getItem('admin_info')).token
  }
  console.log(token)
  if(config.data == '' || config.data == null || config.data ==undefined){
    if (token) {
      config.params['token'] = token
    }
    return config
  }else{
    if (token) {
      config.data['token'] = token
    }
    return config
  }

}, function (error) {

  return Promise.reject(error)
})




/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
