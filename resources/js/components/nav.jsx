import React, { useState, useRef, useEffect } from 'react';
import LoginModal from './modal';
import { Link } from 'react-router-dom';
import "../../css/styles.scss";
import travproLogo from '../../images/travpro.jpg';

const NavBar = () => {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const dropdownRef = useRef(null);
  const sentryActive = "Active"; // This can be dynamic based on your app's state

  // Close dropdown if clicked outside
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setIsDropdownOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  return (
    <div className="navbar">
      <div className="navbar-left">
        <div className="logo">
          <img src={travproLogo} alt="TravPRO Logo" className="logo-image" />
          <span className='logo-text'>TravPRO</span>
        </div>
        <Link to='/' className='link-title'>Developer Dashboard</Link>
      </div>

      <div className="navbar-right">
        <div className="nav-badge">Sentry is {sentryActive}</div>
        
        {/* Menu Button */}
        <div className="menu-container" ref={dropdownRef}>
          <button 
            className="menu-icon dropdown-login" 
            onClick={() => setIsDropdownOpen(!isDropdownOpen)}
          >
            ☰
          </button>

          {/* Dropdown Menu */}
          {isDropdownOpen && (
            <div className="dropdown-menu">
              <button onClick={() => setIsModalOpen(true)}>Login</button>
              <Link to="/profile">Profile</Link>
              <button onClick={() => alert("Logging out...")}>Logout</button>
            </div>
          )}
        </div>
      </div>

      {/* Modal */}
      <LoginModal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)} />
    </div>
  );
};

export { NavBar as default };
