import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import backgroundImage from '../../images/loginBackground.jpg';
import "../../css/styles.scss";

const OpenTicketsPage = () => {
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
      className="ticket-container"
      style={{
        backgroundImage: `url(${backgroundImage})`
      }}
    >
      <div
        className="ticket-page">
        <h1 className="not-found-title">Open Tickets</h1>
        <p className="not-found-message">
            Here you can view and manage your open tickets.
        </p>

        <button onClick={goHome} className="button-login">
          Go to Home Page
        </button>
      </div>
    </div>
  );
};

export { OpenTicketsPage as default };
