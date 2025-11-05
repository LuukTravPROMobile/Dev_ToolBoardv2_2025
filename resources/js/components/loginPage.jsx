import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../src/contexts/AuthContext';
import "../../css/styles.scss";
import backgroundImage from '../../images/loginBackground.jpg';

const LoginPage = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();
  const { login } = useAuth();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setIsLoading(true);

    try {
      const result = await login({ email, password });
      if (result.success) {
        navigate('/dashboard'); // Redirect naar dashboard na succesvol inloggen
      } else {
        setError(result.error || 'Login mislukt. Controleer je gegevens.');
      }
    } catch (err) {
      setError('Er is een probleem opgetreden. Probeer het later opnieuw.');
    } finally {
      setIsLoading(false);
    }
  };

  const goHome = () => navigate('/register');

  return (
    <div
      className="page-container"
      style={{
        backgroundImage: `url(${backgroundImage})`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        backgroundRepeat: "no-repeat",
        minHeight: "100vh",
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        paddingTop: "60px",
      }}
    >
      <div
        className="not-found-page"
        style={{
          background: "rgba(0, 0, 0, 0.5)",
          padding: "40px",
          borderRadius: "10px",
          color: "white",
          maxWidth: "400px",
          width: "100%",
          textAlign: "left", // left-align inside box
        }}
      >
        <h1 className="not-found-title">Sign In</h1>
        <p className="not-found-message">
          Please login to access your dashboard.
        </p>

        <form onSubmit={handleSubmit} className="login-form">
          <div className='login-input-group'>
            <label htmlFor="email">Email:</label>
            <input
              type="email"
              id="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              placeholder="Enter your email"
            />
          </div>

          <div className='login-input-group'>
            <label htmlFor="password">Password:</label>
            <input
              type="password"
              id="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              placeholder="Enter your password"
            />
          </div>

          {error && <div className="error-message" style={{ color: '#ff6b6b', marginBottom: '10px' }}>{error}</div>}
          <button className='button-login' type="submit" disabled={isLoading}>
            {isLoading ? 'Signing in...' : 'Sign in'}
          </button>
        </form>

        <p className="not-found-message">
          No existing account? Register here:
        </p>

        <button onClick={goHome} className="button-login">
          Register Account
        </button>
      </div>
    </div>
  );
};

export { LoginPage as default };
