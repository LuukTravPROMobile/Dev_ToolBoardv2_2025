import React, { useState } from 'react';
import "../../css/styles.scss";
import LoginModal from './modal';

const NavBar = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);

  return (
    <div className="navbar">
      <div className="navbar-left">
        <div className="logo">
          <div className="logo-icon"></div>
          <span>TravPRO</span>
        </div>
        <div className="navbar-title">Developer Dashboard</div>
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

export default NavBar;
