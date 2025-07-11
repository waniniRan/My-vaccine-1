import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

const api = axios.create({
  baseURL: process.env.EXPO_PUBLIC_API_URL || 'http://localhost:8001',
  timeout: 10000,
});

// Request interceptor to add Authorization header and handle PHP form data
api.interceptors.request.use(async (config) => {
  const token = await AsyncStorage.getItem('accessToken');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  
  // For POST requests to PHP scripts, use form-urlencoded
  if (config.method === 'post' && config.url && config.url.endsWith('.php')) {
    config.headers['Content-Type'] = 'application/x-www-form-urlencoded';
    if (config.data && typeof config.data === 'object') {
      config.data = new URLSearchParams(config.data).toString();
    }
  }
  
  return config;
}, (error) => Promise.reject(error));

// Helper to extract data from various response formats
export function extractData(response) {
  if (response?.data) {
    if (typeof response.data === 'object' && 'data' in response.data) {
      return response.data.data;
    }
    return response.data;
  }
  return response;
}

// Add response interceptor
api.interceptors.response.use(
  (response) => {
    response.data = extractData(response);
    return response;
  },
  async (error) => {
    if (error.response && (error.response.status === 401 || error.response.status === 403)) {
      await AsyncStorage.removeItem('accessToken');
      await AsyncStorage.removeItem('refreshToken');
      // Optionally, trigger navigation to login
    }
    return Promise.reject(error);
  }
);

export default api; 