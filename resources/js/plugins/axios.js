import axios from 'axios'
import {app} from '@/app';

let token = document.head.querySelector('meta[name="csrf-token"]');

const instance = axios.create({
    baseURL: '/api/'
});

instance.interceptors.request.use(function (config) {
    // Do something before request is sent
    const token = localStorage.getItem('token');

    if (token) {
        config.headers.common['Authorization'] = `Bearer ${token}`;
    }

    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

instance.interceptors.response.use(
    response => {

        return response;

    }, error => {

        if (error.response.status === 401) {

            app.$router.push({name: 'login'});

        }

        return Promise.reject(error);

    });

export default instance