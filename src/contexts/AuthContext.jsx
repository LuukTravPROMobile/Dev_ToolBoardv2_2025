// src/contexts/AuthContext.jsx

import { createContext, useContext, useState, useEffect } from "react";
import { authAPI } from "../../app/services/api.js";

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [token, setToken] = useState(localStorage.getItem("auth_token"));

    // Check if user is logged in on mount
    useEffect(() => {
        if (token) {
            loadUser();
        } else {
            setLoading(false);
        }
    }, [token]);

    // Load user data
    const loadUser = async () => {
        try {
            const response = await authAPI.getCurrentUser();
            setUser(response.data);
        } catch (error) {
            console.error("Failed to load user:", error);
            logout(); // Clear invalid token
        } finally {
            setLoading(false);
        }
    };

    // Register
    const register = async (data) => {
        try {
            const response = await authAPI.register(data);
            const { user, token } = response.data;

            localStorage.setItem("auth_token", token);
            setToken(token);
            setUser(user);

            return { success: true, data: response.data };
        } catch (error) {
            return {
                success: false,
                error: error.response?.data?.message || "Registration failed",
            };
        }
    };

    // Login
    const login = async (credentials) => {
        try {
            const response = await authAPI.login(credentials);
            const { user, token } = response.data;

            localStorage.setItem("auth_token", token);
            setToken(token);
            setUser(user);

            return { success: true, data: response.data };
        } catch (error) {
            return {
                success: false,
                error: error.response?.data?.message || "Login failed",
            };
        }
    };

    // Logout
    const logout = async () => {
        try {
            await authAPI.logout();
        } catch (error) {
            console.error("Logout error:", error);
        } finally {
            localStorage.removeItem("auth_token");
            setToken(null);
            setUser(null);
        }
    };

    // Logout from all devices
    const logoutAll = async () => {
        try {
            await authAPI.logoutAll();
            localStorage.removeItem("auth_token");
            setToken(null);
            setUser(null);
            return { success: true };
        } catch (error) {
            return {
                success: false,
                error: error.response?.data?.message || "Logout failed",
            };
        }
    };

    const value = {
        user,
        token,
        loading,
        isAuthenticated: !!user,
        register,
        login,
        logout,
        logoutAll,
        loadUser,
    };

    return (
        <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
    );
};

// Custom hook to use auth context
export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error("useAuth must be used within an AuthProvider");
    }
    return context;
};
