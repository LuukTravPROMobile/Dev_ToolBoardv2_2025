import React from 'react';
import "../../css/styles.scss";

const RecentErrors = () => {
    return (
        <div className="card" style={{ marginBottom: '20px' }}>
            <h2 className="card-title">Most Recent Error</h2>
            <p className="card-subtitle">From project error</p>
                    
            <div className="recent-error-card">
                <div className="project-name">
                    travpro-frontend <span className="project-meta">12/pro erauda</span>
                </div>
                <div className="error-message">TypeError: Cannot read properties of undefined</div>
                <div className="error-time">2025-10-20 12:40:11</div>
            </div>
        </div>
    );
};

export { RecentErrors as default };
