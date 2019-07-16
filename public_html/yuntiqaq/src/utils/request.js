import axios from 'axios'
import qs from 'qs'
import { Message, Loading } from 'element-ui'

var isLoading, isSuccessTip, loading

const request = axios.create({
    baseURL: `http://yunti.hongbao19.net/`,
    timeout: 20000,
    headers: { 'Content-Type': 'application/x-www-form-urlencoded'},
    withCredentials: true,
    qs: false,
    isLoading: true,
    isSuccessTip: false
})

request.interceptors.request.use(
    config => {
        // isLoading = config.isLoading || true
        // isSuccessTip = config.isSuccessTip || true
        isLoading && (loading = Loading.service({ text: '正在加载中', background: 'rgba(0, 0, 0, 0.3)' }))
        config.qs && (config.data = qs.stringify(config.data, { indices: false }))
        if (localStorage.getItem('admin_info')) {
            config.headers['token'] = JSON.parse(localStorage.getItem('admin_info')).token
        }
        return config
    },
    err => {
        isLoading && loading.close()
        Message.error('网络状态差')
        return Promise.reject(err)
    }
)

request.interceptors.response.use(
    res => {
        const { data } = res
        isLoading && loading.close()
        isSuccessTip && Message.success(data.message)
        if (data.status === 1) return data.data
        else return Promise.reject(res)
    },
    err => {
        isLoading && loading.close()
        Message.error('网络状态差')
        return Promise.reject(err)
    }
)

export default request