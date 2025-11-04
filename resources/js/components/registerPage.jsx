import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import backgroundImage from '../../images/loginBackground.jpg';
import "../../css/styles.scss";

const RegisterPage = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log('Email:', email);
    console.log('Password:', password);
  };

  const goHome = () => navigate('/');

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
        <h1 className="not-found-title">Register Account</h1>
        <p className="not-found-message">
          Please register in order to log in.
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

          <button className = 'button-login'type="submit">Register</button>
        </form>
      </div>
    </div>
  );
};

export { RegisterPage as default };
