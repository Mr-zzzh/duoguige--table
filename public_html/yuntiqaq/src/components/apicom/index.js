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


// 这是配置了config之后
import request from '@/utils/request'


// 用户管理模块
// 获得用户分类/分类管理的接口
export const getUserTab = (params) => {
    return request.get("admin/user", { params, isLoading: true});
}

//  获得用户分类/获得删除用户
export const delUser = (params) => {
    return request.delete(`admin/user/${params}`, { isSuccessTip: true });
}

// 用户分类页面/读取用户的的详细信息的接口-----去到用户审核的页面，必须有改用户的id
// 获取用户详情的接口
export const getUserInfo = (params) => {
    console.log('test')
    return request.get(`admin/user/${params}`, { params, isLoading: true});
}

// 用户分类页面/读取用户的的详细信息的接口-----去到用户审核的页面，必须有改用户的id
export const getAudit = (params) => {
    // console.log('test')
    return request.get("/admin/user/editstatus", { params, isLoading: true });
}

// 用户分类页面/启用，禁用
export const getForbidden  = (params) => {
    // console.log('test')
    return request.get("/admin/user/forbidden", { params, isLoading: true});
}

// 用户分类页面/大师管理的接口
export const getdashi  = (params) => {
    // console.log('test')
    return request.get("/admin/user/technician", { params, isLoading: true});
}








// 订单管理模块
//  获得全部订单的接口
export const getGoodsOrder = (params) => {
    // console.log('test')
    return request.get("/admin/goodsorder", { params, isLoading: true, isSuccessTip: true });
}

//发货的接口-----这个页面没写
export const getGoodsdeliver = (params) => {
    // console.log('test')
    return request.get("/admin/goodsorder/delive", { params, isLoading: true, isSuccessTip: true });
}

//订单概述
export const Goodssummarize = (params) => {
        // console.log('test')
        return request.get("/admin/goodsorder/summarize", { params, isLoading: true, isSuccessTip: true });
    }
    //删除
export const delGoods = (params) => {
    // console.log('test')
    return request.delete(`/admin/goodsorder/${params}`, { params, isLoading: true, isSuccessTip: true });
}







// 找聘模块-------------求职信息的接口
//  获得招聘管理/
export const getJobs = (params) => {
        // console.log('test')
        return request.get("/admin/jobwanted", { params, isLoading: true, isSuccessTip: true });
    }
    //详情
export const getJobsinfo = (params) => {
        // console.log('test')
        return request.get(`/admin/jobwanted/${params}`, { isLoading: true, isSuccessTip: true });
    }
    //审核
export const jobeditstatus = (params) => {
    // console.log('test')
    return request.get(`/admin/jobwanted/editstatus`, { params, isLoading: true, isSuccessTip: true });
}

// 找聘模块-------------找聘信息的接口
//  获得招聘管理/
export const getRecruit = (params) => {
        // console.log('test')
        return request.get("/admin/invite", { params, isLoading: true, isSuccessTip: true });
    }
    // 详情
export const getRecruitInfo = (params) => {
        // console.log('test')
        return request.get(`/admin/invite/${params}`, { isLoading: true, isSuccessTip: true });
    }
    //审核
export const RecruitEditstatus = (params) => {
    // console.log('test')
    return request.get(`/admin/invite/editstatus`, { params, isLoading: true, isSuccessTip: true });
}





// 意见反馈模块
//  获得意见反馈的接口
export const getFeedback = (params) => {
    // console.log('test')
    return request.get("/admin/feedback", { params, isLoading: true, isSuccessTip: true });
}

export const delFeedback = (params) => {
    // console.log('test')
    return request.delete(`/admin/feedback/${params}`, { params, isLoading: true, isSuccessTip: true });
}







//  维保管理模块---------其实这些接口地址其实差不多都一样，区别在于传参和请求方式
//  获得维保管理的接口------列表
export const getMaintenance = (params) => {
        // console.log('test')
        return request.get("/admin/maintenance", { params, isLoading: true, isSuccessTip: true });
    }
    // 删除
export const delM = (params) => {
        // console.log('test')
        return request.delete(`/admin/maintenance/${params}`, { isSuccessTip: true });
    }
    // 详情
export const getinfo = (params) => {
        // console.log('test')
        return request.get(`/admin/maintenance/${params}`, { isSuccessTip: true });
    }
    // 审核
export const editstatus = (params) => {
    // console.log('test')
    return request.get(`/admin/maintenance/editstatus`, { params, isLoading: true, isSuccessTip: true });
}