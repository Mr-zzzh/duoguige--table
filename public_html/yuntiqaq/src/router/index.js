import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/register',
      name: 'register',
      component: resolve => require(['../components/register.vue'], resolve),
      meta: {
        title: '员工首页',
      },
    },
    {
      path: '/',
      component: resolve => require(['../components/common/Home.vue'], resolve),
      meta: {
        title: '自述文件'
      },
      children: [
        {
          path: '/admin_index',
          name: 'admin_index',
          component: resolve => require(['../components/index/index.vue'], resolve),
          meta: {
            title: '首页',
          },
        },
      ]
    },
  ]
})
