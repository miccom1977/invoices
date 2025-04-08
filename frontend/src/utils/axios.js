import axios from 'axios'
import { getToken } from './token'

const API = axios.create({
  // eslint-disable-next-line no-undef
  baseURL: process.env.VUE_APP_API_URL
})

API.interceptors.request.use(function (config) {
  const token = getToken()
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
}, function (error) {
  // Do something with request error
  return Promise.reject(error)
})

export default API
