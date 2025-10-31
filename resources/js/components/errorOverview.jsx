import React from 'react'
import "../../css/styles.scss";

const ErrorOverview = () => {
    return (
        <div className="card">
            <h2 className="card-title">Error Overview</h2>
            <p className="card-subtitle">(Sentry + Flare logs)</p>
                
            <div className="error-stats">
                <div className="error-stat">
                    <div className="error-stat-label">Today:</div>
                    <div className="error-stat-value">56</div>
                </div>
                <div className="error-stat">
                    <div className="error-stat-label">This Week:</div>
                    <div className="error-stat-value">284</div>
                </div>
                <div className="error-stat">
                    <div className="error-stat-label">This Month:</div>
                    <div className="error-stat-value">920</div>
                </div>
            </div>

            <div className="error-list">
                <h4>Most Common Errors</h4>
                <div className="error-item">
                    <span className="error-text">NetworkError: Failed to fetch</span>
                    <span className="error-count">38</span>
                </div>
                <div className="error-item">
                    <span className="error-text">SyntaxError; unexpected token "&lt;"</span>
                    <span className="error-count">22</span>
                </div>
            </div>

            <div className="bar-chart">
                <div className="bar"></div>
                <div className="bar"></div>
                <div className="bar"></div>
                <div className="bar"></div>
            </div>

            <div className="error-list">
                <h4>Most Recent Error</h4>
                <div className="error-item">
                    <span className="error-text">NetworkError: Failed to fetch</span>
                    <span className="error-count">38</span>
                </div>
                <div className="error-item">
                    <span className="error-text">SyntaxError: Unexpected token '&lt;'</span>
                    <span className="error-count">22</span>
                </div>
            </div>
        </div>
    )
}

export { ErrorOverview as default }
