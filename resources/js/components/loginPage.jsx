import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../authContext";
import "../../css/styles.scss";
import backgroundImage from "../../images/loginBackground.jpg";

const LoginPage = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();
  const { login } = useAuth();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setIsLoading(true);
    try {
      const result = await login({ email, password });
      if (result.success) navigate("/dashboard");
      else setError(result.error || "Login failed");
    } catch {
      setError("Something went wrong");
    } finally {
      setIsLoading(false);
    }
  };

  const goHome = () => navigate("/register");

  return (
    <div
      className="page-container with-background"
      style={{ "--background-image": `url(${backgroundImage})` }}
    >
      <div className="login-box">
        <h1>Sign In</h1>
        <p className='login-text'>Please login to access your dashboard.</p>

        <form
  className="login-form"
  method="POST"
  action="/login"
  onSubmit={handleSubmit}
>
  {/* CSRF token (if needed, e.g., Laravel expects it) */}
  <input type="hidden" name="_token" value={window.csrfToken || ""} />

  <div className="login-input-group">
    <label className='login-text' htmlFor="email">Email:</label>
    <input
      type="email"
      id="email"
      name="email"
      value={email}
      onChange={(e) => setEmail(e.target.value)}
      required
      placeholder="Enter your email"
      className="input-field"
    />
  </div>

  <div className="login-input-group">
    <label className='login-text' htmlFor="password">Password:</label>
    <input
      type="password"
      id="password"
      name="password"
      value={password}
      onChange={(e) => setPassword(e.target.value)}
      required
      placeholder="Enter your password"
      className="input-field"
    />
  </div>

  {error && <div className="error-message">{error}</div>}

  <button className="button-login" type="submit" disabled={isLoading}>
    {isLoading ? "Signing in..." : "Sign in"}
  </button>
</form>

<p className = 'login-text'>No existing account? Register here:</p>
<button className="button-login" onClick={goHome}>
  Register Account
</button>

      </div>
    </div>
  );
};

export default LoginPage;
