
<link rel="stylesheet" href="/public/css/viewEmployee.css?v=<?php echo time(); ?>">

<div class="container-fluid py-4">
 
  <ul class="nav nav-tabs" id="recordTabs" role="tablist" style="border-bottom: none; margin-bottom: 1.5rem; background: transparent; display: flex; justify-content: center; gap: 12px;">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees" type="button" role="tab" aria-selected="true" style="border-radius: 10px; padding: 12px 24px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #3498db, #2980b9); border: none; box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3); transition: all 0.3s ease; min-width: 160px; text-align: center; letter-spacing: 0.5px;">
      <i class="fas fa-users me-2" style="font-size: 16px; "></i> Employees
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="job-titles-tab" data-bs-toggle="tab" data-bs-target="#job-titles" type="button" role="tab" aria-selected="false" style="border-radius: 10px; padding: 12px 24px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #e74c3c, #c0392b); border: none; box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3); transition: all 0.3s ease; min-width: 160px; text-align: center; letter-spacing: 0.5px;">
      <i class="fas fa-briefcase me-2" style="font-size: 16px;"></i> Job Titles
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="departments-tab" data-bs-toggle="tab" data-bs-target="#departments" type="button" role="tab" aria-selected="false" style="border-radius: 10px; padding: 12px 24px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #2ecc71, #27ae60); border: none; box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3); transition: all 0.3s ease; min-width: 160px; text-align: center; letter-spacing: 0.5px;">
      <i class="fas fa-building me-2" style="font-size: 16px;"></i> Departments
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="locations-tab" data-bs-toggle="tab" data-bs-target="#locations" type="button" role="tab" aria-selected="false" style="border-radius: 10px; padding: 12px 24px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #9b59b6, #8e44ad); border: none; box-shadow: 0 4px 10px rgba(155, 89, 182, 0.3); transition: all 0.3s ease; min-width: 160px; text-align: center; letter-spacing: 0.5px;">
      <i class="fas fa-map-marker-alt me-2" style="font-size: 16px;"></i> Locations
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="kra-tab" data-bs-toggle="tab" data-bs-target="#kra" type="button" role="tab" aria-selected="false" style="border-radius: 10px; padding: 12px 24px; font-weight: 600; color: #fff; background: linear-gradient(135deg, #f39c12, #d35400); border: none; box-shadow: 0 4px 10px rgba(243, 156, 18, 0.3); transition: all 0.3s ease; min-width: 160px; text-align: center; letter-spacing: 0.5px;">
      <i class="fas fa-chart-line me-2" style="font-size: 16px;"></i> KRA
    </button>
  </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">

<?php include 'tab-include.php'; ?>
<script src="/public/js/viewEmployee.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>