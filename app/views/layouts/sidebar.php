<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4e73df;
      --primary-dark: #224abe;
      --warning: #f6c23e;
      --light-bg: #f8f9fc;
      --transition-speed: 0.3s;
      --sidebar-width: 250px;
      --sidebar-collapsed-width: 70px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html, body {
      height: 100%;
      font-family: 'Nunito', sans-serif;
    }

    body {
      display: flex;
      background: var(--light-bg);
      overflow-x: hidden;
    }

    /* SIDEBAR */
    .sidebar {
      width: var(--sidebar-width);
      background: linear-gradient(180deg, var(--primary) 10%, var(--primary-dark) 100%);
      color: #fff;
      transition: width var(--transition-speed);
      overflow: hidden;
    }

    .sidebar.collapsed {
      width: var(--sidebar-collapsed-width);
    }

    .sidebar-heading {
      padding: 1.2rem 1rem;
      font-weight: 800;
      font-size: 0.85rem;
      text-transform: uppercase;
      text-align: center;
      white-space: nowrap;
    }

    .nav-item { margin-bottom: 0.25rem; }
    .nav-link {
      display: flex; align-items: center;
      padding: 0.85rem 1rem;
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      white-space: nowrap;
      transition: background 0.2s, transform 0.2s;
    }
    .nav-link i {
      min-width: 2rem;
      margin-right: 0.5rem;
      transition: margin var(--transition-speed);
    }
    .sidebar.collapsed .nav-link i {
      margin-right: 2rem;
    }
    .nav-link.active {
      background: rgba(255,255,255,0.1);
      border-left: 4px solid var(--warning);
      color: #fff; font-weight: 700;
    }
    .nav-link:hover {
      background: rgba(255,255,255,0.1);
      color: #fff;
      transform: translateX(4px);
    }
    .sidebar-divider {
      border-top: 1px solid rgba(255,255,255,0.15);
      margin: 1rem 0;
    }
    .sidebar.collapsed .sidebar-heading,
    .sidebar.collapsed .nav-link span {
      display: none;
    }

    /* MAIN CONTENT */
    .content-wrapper {
      flex: 1;
      display: flex;
      flex-direction: column;
      transition: padding var(--transition-speed);
    }

    /* optional padding tweak when collapsed */
    .content-wrapper.expanded {
      padding-left: 10px;
    }

    .topbar {
      display: flex; align-items: center; justify-content: space-between;
      height: 60px; padding: 0 1rem;
      background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      z-index: 100;
    }

    .sidebar-toggle {
      background: none; border: none; cursor: pointer;
      font-size: 1.2rem; color: var(--primary);
      width: 40px; height: 40px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      transition: background 0.2s, color 0.2s;
    }
    .sidebar-toggle:hover {
      background: rgba(78,115,223,0.1);
      color: var(--primary-dark);
    }

    .container-fluid {
      flex: 1;
      padding: 1.5rem;
      overflow-y: auto;
    }

    /* Responsive: auto collapse */
    @media (max-width: 768px) {
      .sidebar { width: var(--sidebar-collapsed-width); }
      .sidebar .sidebar-heading,
      .sidebar .nav-link span { display: none; }
      .sidebar .nav-link i { margin-right: 2rem; }
    }
  </style>
</head>
<body>

  <div class="sidebar" id="sidebar">
    <div class="sidebar-heading">Admin Dashboard</div>

    <div class="nav-item">
      <a href="/dashboard" class="nav-link active">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
      </a>
    </div>
    <hr class="sidebar-divider">

    <div class="sidebar-heading">Interface</div>
    <div class="nav-item">
      <a href="/setting/view" class="nav-link">
        <i class="fas fa-fw fa-cog"></i>
        <span>Settings</span>
      </a>
    </div>
    <div class="nav-item">
      <a href="/form/view" class="nav-link">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>IBSC</span>
      </a>
    </div>
    <hr class="sidebar-divider">

    <div class="sidebar-heading">User</div>
    <div class="nav-item">
      <a href="/profile" class="nav-link">
        <i class="fas fa-fw fa-user"></i>
        <span>Profile</span>
      </a>
    </div>
    <div class="nav-item">
      <a href="/auth/logout" class="nav-link">
        <i class="fas fa-fw fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </div>
  </div>

  <div class="content-wrapper" id="content-wrapper">
    <nav class="topbar">
      <button id="sidebarToggle" class="sidebar-toggle">
        <i class="fas fa-bars"></i>
      </button>
      <!-- additional topbar content -->
    </nav>

    <div class="container-fluid">


  <script>
    document.getElementById('sidebarToggle').addEventListener('click', () => {
      document.getElementById('sidebar').classList.toggle('collapsed');
      document.getElementById('content-wrapper').classList.toggle('expanded');
    });
  </script>

</body>
</html>
