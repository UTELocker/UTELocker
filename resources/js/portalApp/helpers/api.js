import axios from "axios";

export function get(url, { commit, actionInfo } = {}) {
    return axios.get(url)
}

export function post(url, payload, options) {
    return axios({
        method: 'POST',
        url: url,
        data: payload,
        ...options
    })
}

export function put(url, payload, options) {
    return axios({
        method: 'PUT',
        url: url,
        data: payload,
        ...options
    })
}

export function del(url, options) {
    return axios({
        method: 'DELETE',
        url: url,
        ...options
    })
}

export function interceptors() {
    axios.interceptors.response.use((res) => {
        return res;
    }, (err) => {
        return Promise.reject(err)
    })
}
