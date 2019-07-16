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

import echarts from 'echarts'
Vue.prototype.$echarts = echarts





axios.interceptors.request.use(function(config) {
    let token = ''
    if (localStorage.getItem('admin_info') == null) {
        return config
    } else {
        token = JSON.parse(localStorage.getItem('admin_info')).token
    }
    config.headers['token'] = token;
    return config
    console.log(token);

}, function(error) {

    return Promise.reject(error)
})


axios.interceptors.response.use(function (response) {
    if (response.data.status == -3) {
        router.replace({
            path: '/',
        })
    } 
    return response;
  }, function (error) {
    // 对响应错误做点什么
    return Promise.reject(error);
  });




/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    // store,
    components: { App },
    template: '<App/>'
})