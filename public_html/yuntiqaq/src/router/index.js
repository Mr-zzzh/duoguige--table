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
                    path: '/admin_audit/:id',
                    name: '/admin_audit',
                    component: resolve => require(['../components/user/pope/audit.vue'], resolve),
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
                    path: '/admin_p_audit/:id',
                    name: '/admin_p_audit',
                    component: resolve => require(['../components/user/property/p_audit.vue'], resolve),
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
                    path: '/admin_order',
                    name: 'admin_order',
                    component: resolve => require(['../components/order/order/order.vue'], resolve),
                    meta: {
                        title: '订单概述',
                    },
                },
                {
                    path: '/admin_allOrder',
                    name: 'admin_allOrder',
                    component: resolve => require(['../components/order/allOrder/allOrder.vue'], resolve),
                    meta: {
                        title: '全部订单',
                    },
                },
                {
                    path: '/admin_jobs',
                    name: 'admin_jobs',
                    component: resolve => require(['../components/jobs/jobs/jobs.vue'], resolve),
                    meta: {
                        title: '求职信息',
                    },
                },
                {
                    path: '/admin_wait',
                    name: 'admin_wait',
                    component: resolve => require(['../components/jobs/jobs/wait.vue'], resolve),
                    meta: {
                        title: '求职信息',
                    },
                },
                {
                    path: '/admin_pass',
                    name: 'admin_pass',
                    component: resolve => require(['../components/jobs/jobs/pass.vue'], resolve),
                    meta: {
                        title: '求职信息',
                    },
                },
                {
                    path: '/admin_reject',
                    name: 'admin_reject',
                    component: resolve => require(['../components/jobs/jobs/reject.vue'], resolve),
                    meta: {
                        title: '求职信息',
                    },
                },
                {
                    path: '/admin_recruit',
                    name: 'admin_recruit',
                    component: resolve => require(['../components/jobs/recruit/recruit.vue'], resolve),
                    meta: {
                        title: '招聘信息',
                    },
                },
                {
                    path: '/admin_r_wait',
                    name: 'admin_r_wait',
                    component: resolve => require(['../components/jobs/recruit/r_wait.vue'], resolve),
                    meta: {
                        title: '招聘信息',
                    },
                },
                {
                    path: '/admin_r_pass',
                    name: 'admin_r_pass',
                    component: resolve => require(['../components/jobs/recruit/r_pass.vue'], resolve),
                    meta: {
                        title: '招聘信息',
                    },
                },
                {
                    path: '/admin_r_reject',
                    name: 'admin_r_reject',
                    component: resolve => require(['../components/jobs/recruit/r_reject.vue'], resolve),
                    meta: {
                        title: '招聘信息',
                    },
                },
                {
                    path: '/admin_Feedback',
                    name: 'admin_Feedback',
                    component: resolve => require(['../components/Feedback/Feedback/Feedback.vue'], resolve),
                    meta: {
                        title: '意见反馈列表',
                    },
                },
                {
                    path: '/admin_maintenance',
                    name: 'admin_maintenance',
                    component: resolve => require(['../components/maintenance/maintenance/maintenance.vue'], resolve),
                    meta: {
                        title: '问答列表',
                    },
                },
                {
                    path: '/admin_check',
                    name: 'admin_check',
                    component: resolve => require(['../components/maintenance/maintenance/check.vue'], resolve),
                    meta: {
                        title: '维保信息审核',
                    },
                },
            ]
        },
    ]
})