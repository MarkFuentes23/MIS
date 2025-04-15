<style>/* Sidebar Styles */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    width: 250px;
    background: #2c3e50;
    color: #ecf0f1;
    box-shadow: 3px 0 10px rgba(0,0,0,0.1);
    z-index: 100;
    transition: all 0.3s ease;
    overflow-y: auto;
    padding-top: 70px; /* Space for logo or brand */
}

.sidebar .nav {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar .nav-item {
    margin: 0;
    padding: 0;
}

.sidebar .nav-link {
    display: block;
    padding: 15px 20px;
    color: #ecf0f1;
    text-decoration: none;
    transition: all 0.3s;
    border-left: 4px solid transparent;
    font-size: 15px;
}

.sidebar .nav-link:hover, 
.sidebar .nav-link.active {
    background: #34495e;
    color: #3498db;
    border-left-color: #3498db;
}

.sidebar .nav-link i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.active {
        transform: translateX(0);
    }
    
    .topnav {
        padding-left: 20px;
    }
    
    .main-content {
        margin-left: 0;
        padding-top: 70px;
    }
    
    .topnav .menu-toggle {
        display: block;
    }
}

/* Branding area in sidebar */
.sidebar-brand {
    padding: 20px;
    text-align: center;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-brand img {
    max-width: 70%;
    height: auto;
}

/* Add this to the main-content CSS to accommodate the topnav */
.main-content {
    padding-top: 80px; /* Space for fixed topnav */
}</style>


<aside class="sidebar">
<div class="col-md-2 bg-light sidebar">
  <ul class="nav flex-column">
  <li class="nav-item">
    <li><a href="/main/dashboard">Dashboard</a></li>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/employee/add">Add Employee</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/employee/viewEmployees">View Employees</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/evaluation/index">Evaluation Form</a>
    </li>
  </ul>
</div>
<div class="col-md-10 content">

</aside>
