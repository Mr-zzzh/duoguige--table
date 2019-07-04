import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [{
      path: '/',
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
      children: [{
          path: '/admin_index',
          name: 'admin_index',
          component: resolve => require(['../components/index/index.vue'], resolve),
          meta: {
            title: '首页',
          },
        },
        {
          path: '/admin_index/user/index',
          name: '/admin_index/user/index',
          component: resolve => require(['../components/user/index.vue'], resolve),
          meta: {
            title: '用户管理',
          },
        },
        {
          path: '/admin_index/user/pope',
          name: '/admin_index/user/pope',
          component: resolve => require(['../components/user/pope.vue'], resolve),
          meta: {
            title: '技术大师',
          },
        },
        {
          path: '/admin_index/user/audit',
          name: '/admin_index/user/audit',
          component: resolve => require(['../components/user/audit.vue'], resolve),
          meta: {
            title: '技术大师审核',
          },
        },
        {
          path: '/admin_index/user/property',
          name: '/admin_index/user/property',
          component: resolve => require(['../components/user/property.vue'], resolve),
          meta: {
            title: '物业公司',
          },
        },
        {
          path: '/admin_index/user/p_audit',
          name: '/admin_index/user/p_audit',
          component: resolve => require(['../components/user/p_audit.vue'], resolve),
          meta: {
            title: '物业公司审核',
          },
        },
      ]
    },
  ]
})
