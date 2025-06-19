import axios from 'axios';

axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL || '';

axios.interceptors.response.use(
    res => res,
    error => {
        const msg = error.response?.data?.message || 'Something went wrong.';
        window.dispatchEvent(new CustomEvent('global-error', { detail: msg }));
        return Promise.reject(error);
    }
);

export default axios;
