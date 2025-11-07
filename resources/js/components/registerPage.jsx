import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import backgroundImage from '../../images/loginBackground.jpg';
import "../../css/styles.scss";

const RegisterPage = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      const response = await fetch('http://127.0.0.1:8000/php/register.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
      });
      const data = await response.json();


      try {
        data = JSON.parse(text); // Attempt JSON parsing
      } catch (err) {
        console.error('Failed to parse JSON:', text);
        setError('Server returned invalid response');
        setLoading(false);
        return;
      }

      if (response.ok) {
        console.log('User registered:', data);
        setLoading(false);
        navigate('/login'); // Redirect to login
      } else {
        setError(data.message || 'Registration failed');
        setLoading(false);
      }
    } catch (err) {
      console.error('Network error:', err);
      setError('Network error. Please check your server.');
      setLoading(false);
    }
  };

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
      <div className="not-found-page">
        <h1 className="not-found-title">Register Account</h1>
        <p className="login-text">Please register in order to log in.</p>

        {error && <p style={{ color: 'red' }}>{error}</p>}

        <form onSubmit={handleSubmit} className="login-form">
          <div className='login-input-group'>
            <label className='login-text' htmlFor="email">Email:</label>
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
            <label className='login-text' htmlFor="password">Password:</label>
            <input
              type="password"
              id="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              placeholder="Enter your password"
            />
          </div>

          <button className='button-login' type="submit" disabled={loading}>
            {loading ? 'Registering...' : 'Register'}
          </button>
        </form>
      </div>
    </div>
  );
};

export default RegisterPage;
