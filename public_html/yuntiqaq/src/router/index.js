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
                    path: '/admin_user',
                    name: '/admin_user',
                    component: resolve => require(['../components/user/index/index.vue'], resolve),
                    meta: {
                        title: '用户管理',
                    },
                },
                {
                    path: '/admin_pope',
                    name: '/admin_pope',
                    component: resolve => require(['../components/user/pope/pope.vue'], resolve),
                    meta: {
                        title: '技术大师',
                    },
                },
                {
                    path: '/admin_audit',
                    name: '/admin_audit',
                    component: resolve => require(['../components/user/audit/audit.vue'], resolve),
                    meta: {
                        title: '技术大师审核',
                    },
                },
                {
                    path: '/admin_property',
                    name: '/admin_property',
                    component: resolve => require(['../components/user/property/property.vue'], resolve),
                    meta: {
                        title: '物业公司',
                    },
                },
                {
                    path: '/admin_p_audit',
                    name: '/admin_p_audit',
                    component: resolve => require(['../components/user/p_audit/p_audit.vue'], resolve),
                    meta: {
                        title: '物业公司审核',
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
                {
                  path: '/admin_news',
                  name: 'admin_news',
                  component: resolve => require(['../components/news/news.vue'], resolve),
                  meta: {
                    title: '新闻动态',
                  },
                },
                {
                  path: '/admin_newsadd',
                  name: 'admin_newsadd',
                  component: resolve => require(['../components/news/newsadd.vue'], resolve),
                  meta: {
                    title: '新闻动态新增or编辑',
                  },
                },
                {
                  path: '/admin_newsxq',
                  name: 'admin_newsxq',
                  component: resolve => require(['../components/news/newsxq.vue'], resolve),
                  meta: {
                    title: '新闻动态详情',
                  },
                },

                {
                  path: '/admin_zfset',
                  name: 'admin_zfset',
                  component: resolve => require(['../components/set/zfset/zfset.vue'], resolve),
                  meta: {
                    title: '支付设置',
                  },
                },
                {
                  path: '/admin_noteset',
                  name: 'admin_noteset',
                  component: resolve => require(['../components/set/noteset/noteset.vue'], resolve),
                  meta: {
                    title: '短信接口设置',
                  },
                },
            ]
        },
    ]
})
        
