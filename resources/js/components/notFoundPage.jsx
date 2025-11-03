import React from 'react'
import { Link } from 'react-router-dom';
import "../../css/styles.scss";

const NotFoundPage = () => {
    return (
        <div className='page-container'>
            <div className="not-found-page">
                <h1 className="not-found-title">404 - Page Not Found</h1>
                <p className="not-found-message">
                    Oops! The page you are looking for does not exist.
                </p>
                <div>
                    <Link to="/" className='link-white'>Go home</Link>
                </div>
            </div>
        </div>
    )
}

export {NotFoundPage as default};