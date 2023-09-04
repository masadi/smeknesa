import Vue from 'vue'

// axios
import axios from 'axios'

const axiosIns = axios.create({
  baseURL: app_url+'/api/',
  // You can add your headers here
  // ================================
  // baseURL: 'https://some-domain.com/api/',
  // timeout: 1000,
  // headers: {'X-Custom-Header': 'foobar'}
})
axiosIns.interceptors.request.use(function (config) {
  return config;
}, function (error) {
  return Promise.reject(error);
});
/*axiosIns.interceptors.response.use(
  response => response,
  error => {
    if (error.response && 419 === error.response.status) {
      window.location.reload()
    } else if (error.response && 401 === error.response.status && !error.response.errors) {
      localStorage.removeItem('userData')
      localStorage.removeItem('accessToken')
      localStorage.removeItem('userAbilities')
      this.$router.push('/login')
    } else {
      return Promise.reject(error);
      //return error.response
    }
});*/
axiosIns.interceptors.response.use(function (response) {
  // Any status code that lie within the range of 2xx cause this function to trigger
  // Do something with response data
  return response;
}, function (error) {
  // Any status codes that falls outside the range of 2xx cause this function to trigger
  // Do something with response error
  if(error.response.data){
    if(error.response.data.errors){
      return error.response
    }
  }
  if (error.response && 419 === error.response.status) {
    window.location.reload()
  } else if (error.response && 401 === error.response.status) {
    localStorage.removeItem('userData')
    localStorage.removeItem('accessToken')
    localStorage.removeItem('userAbilities')
    this.$router.push('/login')
  } else {
    return Promise.reject(error);
    //return error.response
  }
});
Vue.prototype.$http = axiosIns

export default axiosIns
