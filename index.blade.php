<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravPRO - Developer Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #1b5198ff;
            color: #1a1a1a;
        }
        
        /* Navigation Bar */
        .navbar {
            background: linear-gradient(135deg, #d44e1dff 0%, #de6114ff 100%);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-left {
            display: flex;
            align-items: center;
            gap: 30px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 1.5em;
            font-weight: 600;
        }
        
        .logo-icon {
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
        }
        
        .navbar-title {
            color: white;
            font-size: 1.3em;
            font-weight: 500;
        }
        
        .navbar-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .nav-badge {
            background: white;
            color: #ff6b35;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
        }
        
        .menu-icon {
            color: white;
            font-size: 1.5em;
            cursor: pointer;
        }
        
        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 40px;
        }
        
        /* Top Stats Cards */
        .top-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .stat-label {
            font-size: 0.95em;
            color: #f3531eff;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .stat-value {
            font-size: 1.8em;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        /* Main Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .card-title {
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a1a1a;
        }
        
        .card-subtitle {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 25px;
        }
        
        /* Error Overview */
        .error-stats {
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
        }
        
        .error-stat {
            flex: 1;
        }
        
        .error-stat-label {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 8px;
        }
        
        .error-stat-value {
            font-size: 2.5em;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .error-list {
            margin-top: 25px;
        }
        
        .error-list h4 {
            font-size: 1em;
            margin-bottom: 15px;
            color: #1a1a1a;
        }
        
        .error-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .error-item:last-child {
            border-bottom: none;
        }
        
        .error-text {
            color: #333;
            font-size: 0.95em;
        }
        
        .error-count {
            font-weight: 600;
            color: #1a1a1a;
        }
        
        /* Bar Chart */
        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 15px;
            height: 100px;
            margin: 20px 0;
        }
        
        .bar {
            flex: 1;
            background:  #de6114ff;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .bar:nth-child(1) { height: 60%; }
        .bar:nth-child(2) { height: 90%; }
        .bar:nth-child(3) { height: 70%; }
        .bar:nth-child(4) { height: 80%; }
        
        /* Recent Error Card */
        .recent-error-card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #ff6b35;
            margin-bottom: 20px;
        }
        
        .project-name {
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        
        .project-meta {
            font-size: 0.85em;
            color: #666;
            margin-bottom: 10px;
        }
        
        .error-message {
            color: #333;
            font-size: 0.95em;
            margin-bottom: 8px;
        }
        
        .error-time {
            font-size: 0.85em;
            color: #999;
        }
        
        /* Comparison Chart */
        .comparison-section {
            margin-top: 25px;
        }
        
        .comparison-title {
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .comparison-subtitle {
            font-size: 0.85em;
            color: #666;
            margin-bottom: 20px;
        }
        
        .chart-container {
            position: relative;
            height: 150px;
            margin-bottom: 10px;
        }
        
        .line-chart {
            width: 100%;
            height: 100%;
            color: #b94310ff;
        }
        
        .chart-percentage {
            text-align: right;
            font-weight: 600;
            color: #b94310ff;
        }
        
        /* Table */
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #e0e0e0;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        /* DigitalOcean Overview */
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .overview-item {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .overview-label {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 5px;
        }
        
        .overview-value {
            font-size: 1.8em;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .overview-subvalue {
            font-size: 0.9em;
            color: #999;
            margin-top: 5px;
        }
        
        /* Bottom Grid */
        .bottom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        @media (max-width: 1200px) {
            .main-grid, .bottom-grid {
                grid-template-columns: 1fr;
            }
            
            .top-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            
            .container {
                padding: 20px;
            }
            
            .top-stats {
                grid-template-columns: 1fr;
            }
            
            .error-stats {
                flex-direction: column;
                gap: 20px;
            }
            
            .overview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <div class="navbar">
        <div class="navbar-left">
            <div class="logo">
                <div class="logo-icon"></div>
                <span>TravPRO</span>
            </div>
            <div class="navbar-title">Developer Dashboard</div>
        </div>
        <div class="navbar-right">
            <div class="nav-badge">Sentry</div>
            <div class="nav-badge">Active</div>
            <div class="menu-icon">☰</div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Top Stats -->
        <div class="top-stats">
            <div class="stat-card">
                <div class="stat-label">Sentry Errors</div>
                <div class="stat-value">Today: <span style="margin-left: 20px;">56</span></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Graduates</div>
                <div class="stat-value">Open Tickets</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Open Tickets</div>
                <div class="stat-value">Last Week</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Droplet Status</div>
                <div class="stat-value">Active</div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="main-grid">
            <!-- Error Overview -->
            <div class="card">
                <h2 class="card-title">Error Overview</h2>
                <p class="card-subtitle">(Sentry + Flare logs)</p>
                
                <div class="error-stats">
                    <div class="error-stat">
                        <div class="error-stat-label">Today:</div>
                        <div class="error-stat-value">56</div>
                    </div>
                    <div class="error-stat">
                        <div class="error-stat-label">This Week:</div>
                        <div class="error-stat-value">284</div>
                    </div>
                    <div class="error-stat">
                        <div class="error-stat-label">This Month:</div>
                        <div class="error-stat-value">920</div>
                    </div>
                </div>

                <div class="error-list">
                    <h4>Most Common Errors</h4>
                    <div class="error-item">
                        <span class="error-text">NetworkError: Failed to fetch</span>
                        <span class="error-count">38</span>
                    </div>
                    <div class="error-item">
                        <span class="error-text">SyntaxError; unexpected token "&lt;"</span>
                        <span class="error-count">22</span>
                    </div>
                </div>

                <div class="bar-chart">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>

                <div class="error-list">
                    <h4>Most Recent Error</h4>
                    <div class="error-item">
                        <span class="error-text">NetworkError: Falleed to fotch</span>
                        <span class="error-count">38</span>
                    </div>
                    <div class="error-item">
                        <span class="error-text">SyntaxError: Unexpected token '&lt;'</span>
                        <span class="error-count">22</span>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Recent Error from Project -->
                <div class="card" style="margin-bottom: 20px;">
                    <h2 class="card-title">Most Recent Error</h2>
                    <p class="card-subtitle">From project error</p>
                    
                    <div class="recent-error-card">
                        <div class="project-name">travpro-frontend <span class="project-meta">12/pro erauda</span></div>
                        <div class="error-message">TypeError: Cannot read properties of undefined</div>
                        <div class="error-time">2025-10-20-12:40:11</div>
                    </div>
                </div>

                <!-- Comparison Card -->
                <div class="card">
                    <h2 class="card-title">Most Recent Error</h2>
                    <p class="card-subtitle">travi: Fco-if error</p>
                    
                    <div class="comparison-section">
                        <h3 class="comparison-title">Comparison</h3>
                        <p class="comparison-subtitle">This Week vs.</p>
                        
                        <div class="chart-container">
                            <svg class="line-chart" viewBox="0 0 400 150">
                                <!-- Eerste lijn -->
                                <path d="M 0 100 Q 50 90, 100 95 T 200 80 T 300 70 T 400 60" 
                                      fill="none" stroke="#ff5703ff" stroke-width="3"/>
                                <!-- Tweede lijn -->
                                <path d="M 0 110 Q 50 100, 100 105 T 200 90 T 300 75 T 400 50" 
                                      fill="none" stroke="#51fff6ff" stroke-width="3"/>
                            </svg>
                        </div>
                        
                        <div class="chart-percentage">+5 %</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="bottom-grid">
            <!-- Ticket Tracker -->
            <div class="card">
                <h2 class="card-title">Ticket Tracker <span style="font-weight: 400; font-size: 0.9em;">(Monday.com)</span></h2>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Team</th>
                                <th>Open</th>
                                <th>Closed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Frontend</td>
                                <td>14</td>
                                <td>32</td>
                            </tr>
                            <tr>
                                <td>Backend</td>
                                <td>22</td>
                                <td>45</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- DigitalOcean Overview -->
            <div class="card">
                <h2 class="card-title">DigitalOcean Overview</h2>
                
                <div class="overview-grid">
                    <div class="overview-item">
                        <div class="overview-label">Active</div>
                        <div class="overview-value">25%</div>
                        <div class="overview-subvalue">Active 60%</div>
                    </div>
                    <div class="overview-item">
                        <div class="overview-label">Active</div>
                        <div class="overview-value">35%</div>
                        <div class="overview-subvalue">Active 50%</div>
                    </div>
                    <div class="overview-item">
                        <div class="overview-label">Active</div>
                        <div class="overview-value">20%</div>
                        <div class="overview-subvalue">Active 70%</div>
                    </div>
                    <div class="overview-item">
                        <div class="overview-label">Active</div>
                        <div class="overview-value">15%</div>
                        <div class="overview-subvalue">Active 65%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>