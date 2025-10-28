// src/services/api.js

import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

// Axios instance maken met basis configuratie
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor - voeg token toe aan elke request
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor - handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token verlopen, logout user
      localStorage.removeItem('auth_token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// API methods
export const authAPI = {
  // Test API connectie
  test: () => api.get('/test'),
  
  // Register
  register: (data) => api.post('/register', data),
  
  // Login
  login: (credentials) => api.post('/login', credentials),
  
  // Get current user
  getCurrentUser: () => api.get('/user'),
};

export const userAPI = {
  // Get all users
  getAll: () => api.get('/users'),
  
  // Get single user
  getById: (id) => api.get(`/users/${id}`),
  
  // Update user
  update: (id, data) => api.put(`/users/${id}`, data),
  
  // Delete user
  delete: (id) => api.delete(`/users/${id}`),
};

export default api;
