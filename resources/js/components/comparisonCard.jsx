import React from 'react';
import "../../css/styles.scss";

const ComparisonCard = () => {
    return (
        <div className="card">
            <h2 className="card-title">Most Recent Error</h2>
            <p className="card-subtitle">travi: Fco-if error</p>
                    
            <div className="comparison-section">
                <h3 className="comparison-title">Comparison</h3>
                <p className="comparison-subtitle">This Week vs.</p>
                        
                <div className="chart-container">
                    <svg className="line-chart" viewBox="0 0 400 150">
                        <path 
                            d="M 0 100 Q 50 90, 100 95 T 200 80 T 300 70 T 400 60"
                            fill="none"
                            stroke="#ff5703ff"
                            strokeWidth="3"
                        />
                        <path 
                            d="M 0 110 Q 50 100, 100 105 T 200 90 T 300 75 T 400 50"
                            fill="none"
                            stroke="#51fff6ff"
                            strokeWidth="3"
                        />
                    </svg>
                </div>
                        
                <div className="chart-percentage">+5%</div>
            </div>
        </div>
    );
};

export { ComparisonCard as default };
