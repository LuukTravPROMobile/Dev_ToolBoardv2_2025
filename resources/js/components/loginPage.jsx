import React from 'react'
import { Link } from 'react-router-dom';
import "../../css/styles.scss";

const LoginPage = () => {
    return (
        <div className='page-container'>
            <div className="not-found-page">
                <h1 className="not-found-title">Login page</h1>
                <p className="not-found-message">
                    Please login to access your dashboard.
                </p>
                <div>
                    <Link to="/" className="link-white">Go home</Link>
                </div>
            </div>
        </div>
    )
}

export {LoginPage as default};