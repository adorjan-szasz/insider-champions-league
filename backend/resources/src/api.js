import axios from 'axios';

const instance = axios.create({
    baseURL: 'http://localhost:8080',
    headers: {
        'Accept': 'application/json',
    },
});

export default instance;
