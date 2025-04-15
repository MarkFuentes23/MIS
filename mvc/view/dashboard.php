<?php 
    include __DIR__ . '/partials/header.php'; 
    include __DIR__ . '/partials/sidebar.php';
    include __DIR__ . '/partials/navbar.php';
?>
<style>
    /* Main Layout Styles */
.main-content {
    padding: 20px;
    margin-left: 250px; /* Adjust based on your sidebar width */
    transition: margin-left 0.3s;
}

/* Dashboard Components */
.dashboard-title {
    color: #333;
    margin-bottom: 25px;
    font-size: 24px;
    font-weight: 600;
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
}

/* Stats Cards */
.overview-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    flex: 1;
    min-width: 220px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.card-header {
    padding: 15px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    font-size: 16px;
    color: #555;
}

.card-icon {
    font-size: 20px;
    color: #3498db;
}

.card-body {
    padding: 20px 15px;
    text-align: center;
}

.stats-number {
    display: block;
    font-size: 28px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 5px;
}

.stats-label {
    color: #7f8c8d;
    font-size: 14px;
}

.card-footer {
    padding: 10px 15px;
    background: #f9f9f9;
    border-top: 1px solid #eee;
    border-radius: 0 0 8px 8px;
}

.growth {
    font-size: 13px;
    font-weight: 500;
}

.growth.positive {
    color: #27ae60;
}

.growth.negative {
    color: #e74c3c;
}

/* Dashboard Rows */
.dashboard-row {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

/* Charts */
.chart-container {
    flex: 2;
    min-width: 500px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.chart-container h3 {
    margin-top: 0;
    color: #333;
    font-size: 18px;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.chart, .chart-placeholder {
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9f9f9;
    border-radius: 5px;
    color: #7f8c8d;
}

.dummy-chart {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #34495e;
}

/* Recent Activities */
.recent-activities {
    flex: 1;
    min-width: 300px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.recent-activities h3 {
    margin-top: 0;
    color: #333;
    font-size: 18px;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.activity-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.activity-item {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 35px;
    height: 35px;
    background: #3498db;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 15px;
}

.activity-details {
    flex: 1;
}

.activity-user {
    font-weight: 600;
    color: #2c3e50;
}

.activity-time {
    display: block;
    font-size: 12px;
    color: #95a5a6;
    margin-top: 3px;
}

/* Tasks Table */
.upcoming-tasks {
    width: 100%;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.upcoming-tasks h3 {
    margin-top: 0;
    color: #333;
    font-size: 18px;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.tasks-table {
    width: 100%;
    border-collapse: collapse;
}

.tasks-table th, .tasks-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

.tasks-table th {
    color: #7f8c8d;
    font-weight: 600;
    font-size: 14px;
}

.tasks-table tr:last-child td {
    border-bottom: none;
}

.tasks-table tr:hover {
    background-color: #f9f9f9;
}

/* Status & Priority Labels */
.status, .priority {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.status.pending {
    background-color: #f1c40f;
    color: #7e6514;
}

.status.in-progress {
    background-color: #3498db;
    color: white;
}

.status.scheduled {
    background-color: #9b59b6;
    color: white;
}

.status.not-started {
    background-color: #95a5a6;
    color: white;
}

.priority.high {
    background-color: #e74c3c;
    color: white;
}

.priority.medium {
    background-color: #f39c12;
    color: white;
}

.priority.critical {
    background-color: #8e44ad;
    color: white;
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
    .chart-container {
        min-width: 400px;
    }
}

@media (max-width: 992px) {
    .main-content {
        margin-left: 0;
    }
    
    .overview-cards {
        flex-direction: column;
    }
    
    .dashboard-row {
        flex-direction: column;
    }
    
    .chart-container {
        min-width: 100%;
    }
}
</style>
<div class="main-content">
    <h2 class="dashboard-title">Dashboard</h2>
    <div class="overview-cards">
        <div class="card">
            <div class="card-header">
                <h3>Kabuuang Users</h3>
                <i class="fa fa-users card-icon"></i>
            </div>
            <div class="card-body">
                <span class="stats-number">2,845</span>
                <span class="stats-label">Registered Users</span>
            </div>
            <div class="card-footer">
                <span class="growth positive">+12% mula noong nakaraang buwan</span>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Aktibong Projects</h3>
                <i class="fa fa-project-diagram card-icon"></i>
            </div>
            <div class="card-body">
                <span class="stats-number">156</span>
                <span class="stats-label">Kasalukuyang Projects</span>
            </div>
            <div class="card-footer">
                <span class="growth positive">+5% mula noong nakaraang buwan</span>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Pending Tasks</h3>
                <i class="fa fa-tasks card-icon"></i>
            </div>
            <div class="card-body">
                <span class="stats-number">47</span>
                <span class="stats-label">Hindi pa nakukumpleto</span>
            </div>
            <div class="card-footer">
                <span class="growth negative">-3% mula noong nakaraang buwan</span>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Revenue</h3>
                <i class="fa fa-peso-sign card-icon"></i>
            </div>
            <div class="card-body">
                <span class="stats-number">₱458,720</span>
                <span class="stats-label">Kabuuang kita</span>
            </div>
            <div class="card-footer">
                <span class="growth positive">+8% mula noong nakaraang buwan</span>
            </div>
        </div>
    </div>
    
    <div class="dashboard-row">
        <div class="chart-container">
            <h3>Aktibidad ng Users</h3>
            <div class="chart" id="userActivityChart">
                <!-- Dito ilalagay ang chart -->
                <div class="chart-placeholder">
                    Chart data loading...
                </div>
            </div>
        </div>
        
        <div class="recent-activities">
            <h3>Mga Pinakabagong Aktibidad</h3>
            <ul class="activity-list">
                <li class="activity-item">
                    <div class="activity-icon"><i class="fa fa-file-plus"></i></div>
                    <div class="activity-details">
                        <span class="activity-user">Juan Dela Cruz</span> ay nagdagdag ng bagong dokumento
                        <span class="activity-time">30 minuto ang nakalipas</span>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon"><i class="fa fa-edit"></i></div>
                    <div class="activity-details">
                        <span class="activity-user">Maria Santos</span> ay nagbago ng project settings
                        <span class="activity-time">1 oras ang nakalipas</span>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon"><i class="fa fa-user-plus"></i></div>
                    <div class="activity-details">
                        <span class="activity-user">Admin</span> ay nagdagdag ng 5 bagong users
                        <span class="activity-time">3 oras ang nakalipas</span>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon"><i class="fa fa-check-circle"></i></div>
                    <div class="activity-details">
                        <span class="activity-user">Jose Rizal</span> ay nakumpleto ang task #254
                        <span class="activity-time">5 oras ang nakalipas</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="dashboard-row">
        <div class="upcoming-tasks">
            <h3>Mga Susunod na Deadline</h3>
            <table class="tasks-table">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Assigned To</th>
                        <th>Priority</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Content Update</td>
                        <td>Maria Santos</td>
                        <td><span class="priority high">High</span></td>
                        <td>Apr 20, 2025</td>
                        <td><span class="status pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td>Database Migration</td>
                        <td>Juan Dela Cruz</td>
                        <td><span class="priority critical">Critical</span></td>
                        <td>Apr 18, 2025</td>
                        <td><span class="status in-progress">In Progress</span></td>
                    </tr>
                    <tr>
                        <td>Client Meeting</td>
                        <td>Admin</td>
                        <td><span class="priority medium">Medium</span></td>
                        <td>Apr 25, 2025</td>
                        <td><span class="status scheduled">Scheduled</span></td>
                    </tr>
                    <tr>
                        <td>System Testing</td>
                        <td>Technical Team</td>
                        <td><span class="priority high">High</span></td>
                        <td>Apr 22, 2025</td>
                        <td><span class="status not-started">Not Started</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Sample script para sa dashboard functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dashboard components
        console.log('Dashboard initialized successfully');
        
        // Placeholder for chart initialization - would normally use Chart.js or similar
        const chartPlaceholder = document.getElementById('userActivityChart');
        if (chartPlaceholder) {
            chartPlaceholder.innerHTML = '<div class="dummy-chart">User Activity Visualization</div>';
        }
    });
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>