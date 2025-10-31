import React from 'react';
import "../../css/styles.scss";

const DigitalOceanOverview = () => {
    return (
        <div className="card">
            <h2 className="card-title">DigitalOcean Overview</h2>
            
            <div className="overview-grid">
                <div className="overview-item">
                    <div className="overview-label">Active</div>
                    <div className="overview-value">25%</div>
                    <div className="overview-subvalue">Active 60%</div>
                </div>
                <div className="overview-item">
                    <div className="overview-label">Active</div>
                    <div className="overview-value">35%</div>
                    <div className="overview-subvalue">Active 50%</div>
                </div>
                <div className="overview-item">
                    <div className="overview-label">Active</div>
                    <div className="overview-value">20%</div>
                    <div className="overview-subvalue">Active 70%</div>
                </div>
                <div className="overview-item">
                    <div className="overview-label">Active</div>
                    <div className="overview-value">15%</div>
                    <div className="overview-subvalue">Active 65%</div>
                </div>
            </div>
        </div>
    )
}

export { DigitalOceanOverview as default };
