


<h2 class="dashboard-title">HR Analytics Dashboard</h2>

<!-- Top stat cards -->
<div class="row">
  <div class="col-xl-3 col-md-12">
    <div class="card border-left-primary">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Employees</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800">152</div>
          </div>
          <div class="col-auto">
            <div class="stat-card-icon">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- New This Month -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-left-success">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">New This Month</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800">8</div>
          </div>
          <div class="col-auto">
            <div class="stat-card-icon">
              <i class="fas fa-user-plus"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Departments -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-left-info">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Departments</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800">6</div>
          </div>
          <div class="col-auto">
            <div class="stat-card-icon">
              <i class="fas fa-clipboard-list"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-left-warning">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Quick Actions</div>
            <a href="/employee/add" class="btn btn-primary mt-2">
              <i class="fas fa-plus"></i>Add Employee
            </a>
          </div>
          <div class="col-auto">
            <div class="stat-card-icon">
              <i class="fas fa-bolt"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts - Fixed container height -->
<div class="row">
  <!-- Growth Trends -->
  <div class="col-xl-8 col-lg-7">
    <div class="card">
      <div class="card-header">
        <h6 class="font-weight-bold text-primary">
          <i class="fas fa-chart-area"></i>Employee Growth Trends
        </h6>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="myAreaChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Department Distribution -->
  <div class="col-xl-4 col-lg-5">
    <div class="card">
      <div class="card-header">
        <h6 class="font-weight-bold text-primary">
          <i class="fas fa-chart-pie"></i>Department Distribution
        </h6>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="myPieChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Employees Table -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h6 class="font-weight-bold text-primary">
          <i class="fas fa-user-clock"></i>Recently Added Employees
        </h6>
        <a href="/employees" class="btn btn-sm btn-primary">
          <i class="fas fa-list"></i>View All
        </a>
      </div>
      <div class="card-body table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Position</th>
              <th>Department</th>
              <th>Start Date</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1528</td>
              <td><strong>John Walker</strong></td>
              <td>Software Engineer</td>
              <td><span class="badge badge-light">Development</span></td>
              <td>May 1, 2025</td>
            </tr>
            <tr>
              <td>1527</td>
              <td><strong>Jessica Chen</strong></td>
              <td>UI/UX Designer</td>
              <td><span class="badge badge-light">Design</span></td>
              <td>Apr 28, 2025</td>
            </tr>
            <tr>
              <td>1526</td>
              <td><strong>Michael Rodriguez</strong></td>
              <td>Marketing Specialist</td>
              <td><span class="badge badge-light">Marketing</span></td>
              <td>Apr 22, 2025</td>
            </tr>
            <tr>
              <td>1525</td>
              <td><strong>Sarah Johnson</strong></td>
              <td>HR Manager</td>
              <td><span class="badge badge-light">Human Resources</span></td>
              <td>Apr 15, 2025</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
// Execute charts after DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // Area Chart
    const ctxA = document.getElementById('myAreaChart').getContext('2d');
    const grad = ctxA.createLinearGradient(0, 0, 0, 300);
    grad.addColorStop(0, "rgba(78, 115, 223, 0.3)");
    grad.addColorStop(1, "rgba(78, 115, 223, 0)");
    
    new Chart(ctxA, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: 'New Employees',
                data: [5, 7, 3, 5, 8, 10, 8, 12, 9, 8, 6, 4],
                fill: true,
                backgroundColor: grad,
                borderColor: "rgba(78, 115, 223, 1)",
                tension: 0.3,
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "#fff",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "#fff"
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: "rgba(0, 0, 0, 0.05)"
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Pie Chart
    const deptLabels = ["Development", "Marketing", "Finance", "HR", "Design", "Operations"];
    const deptData = [42, 28, 21, 15, 25, 21];
    const deptColors = [
        "rgba(78, 115, 223, 0.8)", 
        "rgba(28, 200, 138, 0.8)", 
        "rgba(54, 185, 204, 0.8)",
        "rgba(246, 194, 62, 0.8)",
        "rgba(231, 74, 59, 0.8)",
        "rgba(133, 135, 150, 0.8)"
    ];
    
    const ctxP = document.getElementById('myPieChart').getContext('2d');
    
    new Chart(ctxP, {
        type: 'doughnut',
        data: {
            labels: deptLabels,
            datasets: [{
                data: deptData,
                backgroundColor: deptColors,
                borderColor: "#ffffff",
                borderWidth: 2,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15
                    }
                }
            }
        }
    });
});
</script>