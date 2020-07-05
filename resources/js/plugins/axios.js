import axios from 'axios'
import {app} from '@/app';

const instance = axios.create({
    baseURL: '/api/'
});

instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
instance.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
instance.defaults.headers.common.crossDomain = true;

let csrf_token = document.head.querySelector('meta[name="csrf-token"]');

if (csrf_token) {
    instance.defaults.headers.common['X-CSRF-TOKEN'] = csrf_token.content;
} else {
    console.error('CSRF token not found');
}

instance.interceptors.request.use(function (config) {
    // Do something before request is sent
    const token = localStorage.getItem('token');

    if (token) {
        config.headers.common['Authorization'] = `Bearer ${token}`;
        if (app.$route.params.group_slug !== undefined) {
            config.params = {...config.params, group_slug: app.$route.params.group_slug};
        }
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
            localStorage.removeItem('token');
            app.$router.push({name: 'login'});

        }

        if (error.response.status === 502) {
            localStorage.removeItem('token');
            setTimeout(() => {
                window.location.href = error.response.data.redirect;
            }, 4000);
        }

        if (error.response.status === 510) {
            app.$router.push({name: error.response.data.redirect});
        }

        if (error.response.status === 403) {
            app.$router.push({name: error.response.data.redirect});
        }

        return Promise.reject(error);

    });

export default instance