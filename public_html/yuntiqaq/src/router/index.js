import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [
    {
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
      children: [
        {
          path: '/admin_index',
          name: 'admin_index',
          component: resolve => require(['../components/index/index.vue'], resolve),
          meta: {
            title: '首页',
          },
        },
        {
          path: '/admin_flgl',
          name: 'admin_flgl',
          component: resolve => require(['../components/shopgl/flgl/flgl.vue'], resolve),
          meta: {
            title: '分类管理',
          },
        },
        {
          path: '/admin_dtgl',
          name: 'admin_dtgl',
          component: resolve => require(['../components/shopgl/dtgl/dtgl.vue'], resolve),
          meta: {
            title: '电梯管理',
          },
        },
        {
          path: '/admin_dtgladd',
          name: 'admin_dtgladd',
          component: resolve => require(['../components/shopgl/dtgl/dtgladd.vue'], resolve),
          meta: {
            title: '电梯管理新增or编辑',
          },
        },
        {
          path: '/admin_label',
          name: 'admin_label',
          component: resolve => require(['../components/shopgl/label/label.vue'], resolve),
          meta: {
            title: '标签管理',
          },
        },
      ]
    },
  ]
})
