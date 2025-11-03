import React, { useState } from 'react';
import LoginModal from './modal';
import { Link } from 'react-router-dom';
import "../../css/styles.scss";

const NavBar = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);

  return (
    <div className="navbar">
      <div className="navbar-left">
        <div className="logo">
          <div className="logo-icon"></div>
          <span>TravPRO</span>
        </div>
        <Link to='/' className='link-title'>Developer Dashboard</Link>
      </div>

      <div className="navbar-right">
        <div className="nav-badge">Sentry</div>
        <div className="nav-badge">Active</div>
        <button className="menu-icon" onClick={() => setIsModalOpen(true)}>
          ☰
        </button>
      </div>

      <LoginModal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)} />
    </div>
  );
};

export {NavBar as default };