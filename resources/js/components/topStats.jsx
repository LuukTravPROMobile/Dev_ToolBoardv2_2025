import React from 'react';
import "../../css/styles.scss";

const TopStats = () => {
    return (
        <div className="top-stats">
            <div className="stat-card">
                <div className="stat-label">Sentry Errors</div>
                <div className="stat-value">
                    Today: <span style={{ marginLeft: '20px' }}>56</span>
                </div>
            </div>
            <div className="stat-card">
                <div className="stat-label">Graduates</div>
                <div className="stat-value">Open Tickets</div>
            </div>
            <div className="stat-card">
                <div className="stat-label">Open Tickets</div>
                <div className="stat-value">Last Week</div>
            </div>
            <div className="stat-card">
                <div className="stat-label">Droplet Status</div>
                <div className="stat-value">Active</div>
            </div>
        </div>
    );
};

export { TopStats as default };
