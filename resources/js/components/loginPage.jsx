import { useState, createContext, useContext, useEffect } from "react";
import axios from "axios";

// Axios configuratie
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://localhost:8000";

// Auth Context
const AuthContext = createContext();

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  // CSRF token ophalen
  const getCsrfToken = async () => {
    await axios.get("/sanctum/csrf-cookie");
  };

  // Check of gebruiker ingelogd is
  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    try {
      const response = await axios.get("/api/user");
      setUser(response.data);
    } catch (error) {
      setUser(null);
    } finally {
      setLoading(false);
    }
  };

  const login = async (email, password) => {
    await getCsrfToken();
    const response = await axios.post("/api/login", { email, password });
    setUser(response.data.user);
    return response.data;
  };

  const register = async (name, email, password, password_confirmation) => {
    await getCsrfToken();
    const response = await axios.post("/api/register", {
      name,
      email,
      password,
      password_confirmation,
    });
    setUser(response.data.user);
    return response.data;
  };

  const logout = async () => {
    await axios.post("/api/logout");
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, login, register, logout, loading }}>
      {children}
    </AuthContext.Provider>
  );
};

// Login Component
export const LoginForm = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const { login } = useAuth();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    try {
      await login(email, password);
      // Redirect of success message
    } catch (err) {
      setError(err.response?.data?.message || "Er is iets misgegaan");
    }
  };

  return (
    <div className="login-form">
      <h2>Inloggen</h2>
      {error && <div className="error">{error}</div>}

      <form onSubmit={handleSubmit}>
        <div>
          <label htmlFor="email">Email:</label>
          <input
            type="email"
            id="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>

        <div>
          <label htmlFor="password">Wachtwoord:</label>
          <input
            type="password"
            id="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>

        <button type="submit">Inloggen</button>
      </form>
    </div>
  );
};

// Register Component
export const RegisterForm = () => {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  const [error, setError] = useState("");
  const { register } = useAuth();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");

    try {
      await register(
        formData.name,
        formData.email,
        formData.password,
        formData.password_confirmation
      );
      // Redirect of success message
    } catch (err) {
      setError(err.response?.data?.message || "Er is iets misgegaan");
    }
  };

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  return (
    <div className="register-form">
      <h2>Registreren</h2>
      {error && <div className="error">{error}</div>}

      <form onSubmit={handleSubmit}>
        <div>
          <label htmlFor="name">Naam:</label>
          <input
            type="text"
            id="name"
            name="name"
            value={formData.name}
            onChange={handleChange}
            required
          />
        </div>

        <div>
          <label htmlFor="email">Email:</label>
          <input
            type="email"
            id="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            required
          />
        </div>

        <div>
          <label htmlFor="password">Wachtwoord:</label>
          <input
            type="password"
            id="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            required
          />
        </div>

        <div>
          <label htmlFor="password_confirmation">Bevestig wachtwoord:</label>
          <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            value={formData.password_confirmation}
            onChange={handleChange}
            required
          />
        </div>

        <button type="submit">Registreren</button>
      </form>
    </div>
  );
};

// Protected Route Component
export const ProtectedRoute = ({ children }) => {
  const { user, loading } = useAuth();

  if (loading) {
    return <div>Laden...</div>;
  }

  if (!user) {
    return <LoginForm />;
  }

  return children;
};

// App.jsx voorbeeld
export const App = () => {
  return (
    <AuthProvider>
      <div className="app">
        <ProtectedRoute>
          <Dashboard />
        </ProtectedRoute>
      </div>
    </AuthProvider>
  );
};

const Dashboard = () => {
  const { user, logout } = useAuth();

  return (
    <div>
      <h1>Welkom, {user.name}!</h1>
      <button onClick={logout}>Uitloggen</button>
    </div>
  );
};
