  <div class="tab-pane fade show active" id="employees" role="tabpanel" aria-labelledby="employees-tab">
  <div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header bg-gradient-primary-to-secondary py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-white">Employee List</h6>
      <button type="button" class="btn btn-light btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
        <i class="fas fa-plus me-1"></i> Add Employee
      </button>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="employeesTable" width="100%" cellspacing="0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">ID</th>
              <th>Full Name</th>
              <th>Location</th>
              <th>Department</th>
              <th>Job Title</th>
              <th>Evaluation</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($employees as $employee): ?>
            <?php 
              // Concatenate name parts
              $fullName = $employee['lastname'] . ', ' . $employee['firstname'];
              if (!empty($employee['middlename'])) {
                $fullName .= ' ' . substr($employee['middlename'], 0, 1) . '.';
              }
              if (!empty($employee['suffix'])) {
                $fullName .= ' ' . $employee['suffix'];
              }
            ?>
            <tr>
              <td class="ps-4"><?= $employee['id'] ?></td>
              <td><?= $fullName ?></td>
              <td><?= $employee['location'] ?? '' ?></td>
              <td><?= $employee['department'] ?? '' ?></td>
              <td><?= $employee['job_title'] ?? '' ?></td>
              <td><?= number_format($employee['evaluation'], 1) ?></td>
              <td class="text-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-primary rounded-start edit-employee-btn" 
                          data-id="<?= $employee['id'] ?>"
                          data-firstname="<?= $employee['firstname'] ?>"
                          data-lastname="<?= $employee['lastname'] ?>"
                          data-middlename="<?= $employee['middlename'] ?? '' ?>"
                          data-suffix="<?= $employee['suffix'] ?? '' ?>"
                          data-location="<?= $employee['location'] ?? '' ?>"
                          data-department="<?= $employee['department'] ?? '' ?>"
                          data-job-title="<?= $employee['job_title'] ?? '' ?>"
                          data-evaluation="<?= $employee['evaluation'] ?? '0.0' ?>">
                    <i class="fas fa-edit"></i>
                  </button>
                  <a href="/setting/delete/<?= $employee['id'] ?>" class="btn btn-sm btn-outline-danger rounded-end delete-employee-btn">
                    <i class="fas fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    </div>
  </div>

  
<!-- Add Employee Modal -->
  <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="/setting/add" method="POST" id="addEmployeeForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="addEmployeeModalLabel">Add Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="firstname" name="firstname" required>
            </div>
            <div class="col-md-6">
              <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="lastname" name="lastname" required>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="middlename" class="form-label">Middle Name</label>
              <input type="text" class="form-control" id="middlename" name="middlename">
            </div>
            <div class="col-md-6">
              <label for="suffix" class="form-label">Suffix</label>
              <input type="text" class="form-control" id="suffix" name="suffix" placeholder="Jr., Sr., III, etc.">
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-3">
              <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
              <select class="form-select" id="location" name="location" required>
                <option value="">Select Location</option>
                <?php foreach($locations as $loc): ?>
                  <option value="<?= $loc['location'] ?>"><?= $loc['location'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="department" class="form-label">Department <span class="text-danger">*</span></label>
              <select class="form-select" id="department" name="department" required>
                <option value="">Select Department</option>
                <?php foreach($departments as $dept): ?>
                  <option value="<?= $dept['department'] ?>"><?= $dept['department'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
              <select class="form-select" id="job_title" name="job_title" required>
                <option value="">Select Job Title</option>
                <?php foreach($job_titles as $job): ?>
                  <option value="<?= $job['job_title'] ?>"><?= $job['job_title'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="evaluation" class="form-label">Evaluation Score</label>
              <input type="number" class="form-control" id="evaluation" name="evaluation" min="0" max="10" step="0.1" value="0.0">
              <small class="form-text text-muted">Score from 0.0 to 10.0</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Save
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="/setting/update" method="POST" id="editEmployeeForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="editEmployeeModalLabel">Edit Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_employee_id">
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_firstname" class="form-label">First Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_firstname" name="firstname" required>
            </div>
            <div class="col-md-6">
              <label for="edit_lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="edit_lastname" name="lastname" required>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit_middlename" class="form-label">Middle Name</label>
              <input type="text" class="form-control" id="edit_middlename" name="middlename">
            </div>
            <div class="col-md-6">
              <label for="edit_suffix" class="form-label">Suffix</label>
              <input type="text" class="form-control" id="edit_suffix" name="suffix" placeholder="Jr., Sr., III, etc.">
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-3">
              <label for="edit_location" class="form-label">Location <span class="text-danger">*</span></label>
              <select class="form-select" id="edit_location" name="location" required>
                <option value="">Select Location</option>
                <?php foreach($locations as $loc): ?>
                  <option value="<?= $loc['location'] ?>"><?= $loc['location'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="edit_department" class="form-label">Department <span class="text-danger">*</span></label>
              <select class="form-select" id="edit_department" name="department" required>
                <option value="">Select Department</option>
                <?php foreach($departments as $dept): ?>
                  <option value="<?= $dept['department'] ?>"><?= $dept['department'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="edit_job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
              <select class="form-select" id="edit_job_title" name="job_title" required>
                <option value="">Select Job Title</option>
                <?php foreach($job_titles as $job): ?>
                  <option value="<?= $job['job_title'] ?>"><?= $job['job_title'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="edit_evaluation" class="form-label">Evaluation Score</label>
              <input type="number" class="form-control" id="edit_evaluation" name="evaluation" min="0" max="10" step="0.1" value="0.0">
              <small class="form-text text-muted">Score from 0.0 to 10.0</small>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>