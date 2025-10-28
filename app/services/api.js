import axios from "axios";

const api = axios.create({
    baseURL: "/api",
    headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content"),
    },
});

export const authAPI = {
    login: async (credentials) => {
        try {
            const response = await api.post("/login", credentials);
            return response.data;
        } catch (error) {
            throw error.response?.data?.message || "An error occurred";
        }
    },
    logout: async () => {
        try {
            const response = await api.post("/logout");
            return response.data;
        } catch (error) {
            throw error.response?.data?.message || "An error occurred";
        }
    },
};

export const usersAPI = {
    getUsers: async () => {
        try {
            const response = await api.get("/users");
            return response.data;
        } catch (error) {
            throw error.response?.data?.message || "An error occurred";
        }
    },
};

const API_URL = "http://localhost:8000/api";

// Axios instance maken met basis configuratie
const api1 = axios.create({
    baseURL: API_URL,
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
    withCredentials: true, // BELANGRIJK voor Sanctum!
});

// Request interceptor - voeg token toe aan elke request
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("auth_token");
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
            // Token verlopen of ongeldig, logout user
            localStorage.removeItem("auth_token");
            localStorage.removeItem("user");
            window.location.href = "/login";
        }
        return Promise.reject(error);
    }
);

// API methods
export const authAPI2 = {
    // Test API connectie
    test: () => api.get("/test"),

    // Register
    register: (data) => api.post("/auth/register", data),

    // Login
    login: (credentials) => api.post("/auth/login", credentials),

    // Logout
    logout: () => api.post("/auth/logout"),

    // Logout from all devices
    logoutAll: () => api.post("/auth/logout-all"),

    // Get current user
    getCurrentUser: () => api.get("/auth/user"),

    // Refresh token
    refreshToken: () => api.post("/auth/refresh"),
};

export const userAPI = {
    // Get all users
    getAll: (params) => api.get("/users", { params }),

    // Get single user
    getById: (id) => api.get(`/users/${id}`),

    // Create user
    create: (data) => api.post("/users", data),

    // Update user
    update: (id, data) => api.put(`/users/${id}`, data),

    // Delete user
    delete: (id) => api.delete(`/users/${id}`),

    // Get statistics
    statistics: () => api.get("/users/statistics"),

    // Bulk delete
    bulkDelete: (ids) => api.post("/users/bulk-delete", { ids }),

    // Toggle status
    toggleStatus: (id) => api.post(`/users/${id}/toggle-status`),
};

export default api;
