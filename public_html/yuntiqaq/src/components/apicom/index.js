// 简单的把请求的都封装在这里面，请求拦截，token不用我做

import axios from "axios"


// 获得电梯管理/分类管理的借口
export const getCategory = () => {
    return axios.get("/admin/brand");
}