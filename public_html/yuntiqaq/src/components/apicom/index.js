// 简单的把请求的都封装在这里面，请求拦截，token不用我做

import axios from "axios"
axios.defaults.baseURL = 'http://yunti.hongbao19.net/';

// 获得用户分类/分类管理的借口
export const getCategory = (parmams) => {
    return axios.get("admin/user", { parmams });
}