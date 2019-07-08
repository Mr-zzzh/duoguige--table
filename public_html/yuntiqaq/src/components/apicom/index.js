// import axios from "axios"
// axios.defaults.baseURL = 'http://yunti.hongbao19.net/';
// // 获得用户分类/分类管理的接口
// export const getUserTab = (params) => {
//     return axios.get("admin/user",{});
// }

// //  获得用户分类/获得删除用户
// export const delUser = (params) => {
//     return axios.delete(`admin/user/${params}`,{});
// }

// //  获得用户分类/用户管理
// export const getPopeTab = (params) => {

//     return axios.get(`admin/user/${params}`);
// }


// 这是配置了config之后
import request from '@/utils/request'


// 用户管理模块
// 获得用户分类/分类管理的接口
export const getUserTab = (params) => {
    return request.get("admin/user", { params, isLoading: true, isSuccessTip: true });
}

//  获得用户分类/获得删除用户
export const delUser = (params) => {
    return request.delete(`admin/user/${params}`, { isSuccessTip: true });
}

//  获得用户分类/用户管理
export const getPopeTab = (params) => {
    // console.log('test')
    return request.get(`admin/user/${params}`);
}


// 用户分类页面/读取用户的的详细信息的接口-----去到用户审核的页面，必须有改用户的id
// 获取用户详情的接口
export const getUserInfo = (params) => {
    console.log('test')
    return request.get(`admin/user/${params}`,{ params, isLoading: true, isSuccessTip: true });
}

// 用户分类页面/读取用户的的详细信息的接口-----去到用户审核的页面，必须有改用户的id
export const getAudit = (params) => {
    // console.log('test')
    return request.get("/admin/user/editstatus", { params, isLoading: true, isSuccessTip: true });
}

// 找聘模块
//  获得招聘管理/求职信息的接口
export const getJobs = (params) => {
    // console.log('test')
    return request.get("/admin/jobwanted", { params, isLoading: true, isSuccessTip: true });
}

//  获得招聘管理/找聘信息的接口
export const getRecruit = (params) => {
    // console.log('test')
    return request.get("/admin/invite", { params, isLoading: true, isSuccessTip: true });
}




// 意见反馈模块
//  获得意见反馈的接口
export const getFeedback = (params) => {
    // console.log('test')
    return request.get("/admin/feedback", { params, isLoading: true, isSuccessTip: true });
}


// 维保管理模块
//  获得维保管理的接口
export const getMaintenance = (params) => {
    // console.log('test')
    return request.get("/admin/maintenance", { params, isLoading: true, isSuccessTip: true });
}


// 订单管理模块
//  获得全部订单的接口
export const getGoodsOrder = (params) => {
    // console.log('test')
    return request.get("/admin/goodsorder", { params, isLoading: true, isSuccessTip: true });
}