import React from 'react'
import ReactDOM from 'react-dom/client'
import "../../css/styles.scss";

const NavBar = () => {
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
                <div className="menu-icon">☰</div>
            </div>
        </div>
    )
}

export {NavBar as default}