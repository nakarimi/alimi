// axios
import axios from 'axios'

const baseURL = 'http://localhost:8000/api/';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');

export default axios.create({
    baseURL
    // You can add your headers here
})