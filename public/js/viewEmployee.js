document.addEventListener('DOMContentLoaded', function() {
  // Initialize datatables
  if (document.getElementById('employeesTable')) {
    $('#employeesTable').DataTable({
      responsive: true,
      language: {
        search: "Search employees:",
        lengthMenu: "Show _MENU_ employees per page",
        info: "Showing _START_ to _END_ of _TOTAL_ employees"
      }
    });
  }
  
  if (document.getElementById('jobTitlesTable')) {
    $('#jobTitlesTable').DataTable({
      responsive: true,
      language: {
        search: "Search job titles:",
        lengthMenu: "Show _MENU_ job titles per page",
        info: "Showing _START_ to _END_ of _TOTAL_ job titles"
      }
    });
  }
  
  if (document.getElementById('departmentsTable')) {
    $('#departmentsTable').DataTable({
      responsive: true,
      language: {
        search: "Search departments:",
        lengthMenu: "Show _MENU_ departments per page",
        info: "Showing _START_ to _END_ of _TOTAL_ departments"
      }
    });
  }
  
  if (document.getElementById('locationsTable')) {
    $('#locationsTable').DataTable({
      responsive: true,
      language: {
        search: "Search locations:",
        lengthMenu: "Show _MENU_ locations per page",
        info: "Showing _START_ to _END_ of _TOTAL_ locations"
      }
    });
  }
  
  // Make sure KRA DataTable is properly initialized
  if (document.getElementById('kraTable')) {
    try {
      $('#kraTable').DataTable({
        responsive: true,
        language: {
          search: "Search KRAs:",
          lengthMenu: "Show _MENU_ KRAs per page",
          info: "Showing _START_ to _END_ of _TOTAL_ KRAs",
          zeroRecords: "No KRA records found",
          emptyTable: "No KRA data available"
        }
      });
      console.log('KRA DataTable initialized successfully');
    } catch (error) {
      console.error('Error initializing KRA DataTable:', error);
    }
  } else {
    console.warn('KRA table element not found');
  }
  
  // Employee - Edit button click handler
  document.querySelectorAll('.edit-employee-btn').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const firstname = this.getAttribute('data-firstname');
      const lastname = this.getAttribute('data-lastname');
      const middlename = this.getAttribute('data-middlename');
      const suffix = this.getAttribute('data-suffix');
      const location = this.getAttribute('data-location');
      const department = this.getAttribute('data-department');
      const jobTitle = this.getAttribute('data-job-title');
      const evaluation = this.getAttribute('data-evaluation');
      
      document.getElementById('edit_employee_id').value = id;
      document.getElementById('edit_firstname').value = firstname;
      document.getElementById('edit_lastname').value = lastname;
      document.getElementById('edit_middlename').value = middlename;
      document.getElementById('edit_suffix').value = suffix;
      document.getElementById('edit_location').value = location;
      document.getElementById('edit_department').value = department;
      document.getElementById('edit_job_title').value = jobTitle;
      document.getElementById('edit_evaluation').value = evaluation;
      
      const editModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
      editModal.show();
    });
  });
  
  // Job Title - Edit button click handler
  document.querySelectorAll('.edit-job-btn').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const title = this.getAttribute('data-title');
      
      document.getElementById('edit_job_id').value = id;
      document.getElementById('edit_job_title_input').value = title;
      
      const editModal = new bootstrap.Modal(document.getElementById('editJobTitleModal'));
      editModal.show();
    });
  });
  
  // Department - Edit button click handler
  document.querySelectorAll('.edit-dept-btn').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');
      
      document.getElementById('edit_dept_id').value = id;
      document.getElementById('edit_department_input').value = name;
      
      const editModal = new bootstrap.Modal(document.getElementById('editDepartmentModal'));
      editModal.show();
    });
  });
  
  // Location - Edit button click handler
  document.querySelectorAll('.edit-loc-btn').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');
      
      document.getElementById('edit_loc_id').value = id;
      document.getElementById('edit_location_input').value = name;
      
      const editModal = new bootstrap.Modal(document.getElementById('editLocationModal'));
      editModal.show();
    });
  });
  
  // KRA - Edit button click handler (simplified and fixed)
  document.querySelectorAll('.edit-kra-btn').forEach(button => {
    button.addEventListener('click', function() {
      try {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        console.log('KRA Edit button clicked, data:', id, name);
        
        // Set values to form fields
        const idField = document.getElementById('edit_kra_id');
        const nameField = document.getElementById('edit_kra_input');
        
        if (!idField || !nameField) {
          console.error('KRA form fields not found!');
          return;
        }
        
        idField.value = id;
        nameField.value = name;
        
        // Show the modal using Bootstrap
        const modal = document.getElementById('editKraModal');
        if (!modal) {
          console.error('KRA Modal not found in DOM!');
          return;
        }
        
        const editModal = new bootstrap.Modal(modal);
        editModal.show();
        console.log('KRA Modal shown successfully');
      } catch (error) {
        console.error('Error in KRA edit button handler:', error);
      }
    });
  });
  
  // Form validation
  const validateForm = (formId) => {
    const form = document.getElementById(formId);
    if (form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    } else {
      console.warn(`Form with ID ${formId} not found in the document.`);
    }
  };
  
  // Apply validation to all forms
  validateForm('addEmployeeForm');
  validateForm('editEmployeeForm');
  validateForm('addJobForm');
  validateForm('editJobForm');
  validateForm('addDeptForm');
  validateForm('editDeptForm');
  validateForm('addLocForm');
  validateForm('editLocForm');
  validateForm('addKraForm');
  validateForm('editKraForm');
  
  // Delete confirmation handlers
  document.querySelectorAll('.delete-employee-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to delete this employee?')) {
        e.preventDefault();
      }
    });
  });
  
  document.querySelectorAll('.delete-job-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to delete this job title?')) {
        e.preventDefault();
      }
    });
  });
  
  document.querySelectorAll('.delete-dept-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to delete this department?')) {
        e.preventDefault();
      }
    });
  });
  
  document.querySelectorAll('.delete-loc-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to delete this location?')) {
        e.preventDefault();
      }
    });
  });
  
  document.querySelectorAll('.delete-kra-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      if (!confirm('Are you sure you want to delete this KRA?')) {
        e.preventDefault();
      }
    });
  });
  
  // Tab handling for records page
  const tabLinks = document.querySelectorAll('a[data-bs-toggle="tab"]');
  if (tabLinks.length > 0) {
    // Store active tab in sessionStorage
    tabLinks.forEach(tab => {
      tab.addEventListener('shown.bs.tab', function(e) {
        sessionStorage.setItem('activeTab', e.target.getAttribute('href'));
      });
    });
    
    // Restore active tab on page load
    const activeTab = sessionStorage.getItem('activeTab');
    if (activeTab) {
      const tab = document.querySelector(`a[href="${activeTab}"]`);
      if (tab) {
        const bsTab = new bootstrap.Tab(tab);
        bsTab.show();
      }
    }
  }
  
  // Check if Bootstrap is available
  if (typeof bootstrap === 'undefined') {
    console.error('Bootstrap not loaded! Modal functionality will not work.');
  } else {
    console.log('Bootstrap loaded successfully.');
  }
  
  // Check if jQuery and DataTables are available
  if (typeof $ === 'undefined') {
    console.error('jQuery not loaded! DataTables functionality will not work.');
  } else if (!$.fn.DataTable) {
    console.error('DataTables plugin not loaded!');
  } else {
    console.log('jQuery and DataTables loaded successfully.');
  }
});