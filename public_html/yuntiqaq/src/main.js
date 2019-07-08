// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'

Vue.config.productionTip = false

// 注册vuex
// import Vuex from 'vuex'

// Vue.use(Vuex)
// var store = new Vuex.Store({
//     state: {
//         user: {}
//     },
//     mutations: {
//         // 把值存入这里，
//         // getUserInfo(state, userInfo) {
//         //     state.user = userInfo;
//         //     localStorage.setItem("user", JSON.stringify(state.user));
//         //     console.log(JSON.stringify(state.user));
//         // }
//     },
//     getters: {},
// })


Vue.prototype.$axios = axios;
import global from './components/apicom/apicom'
axios.defaults.withCredentials = true; //让ajax携带cookie
import axios from 'axios';
Vue.prototype.api = global.apicom // 绑定原型链的公共api

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);


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




/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    // store,
    components: { App },
    template: '<App/>'
})