  <!-- Departments Tab -->
  <div class="tab-pane fade" id="departments" role="tabpanel" aria-labelledby="departments-tab">
    <div class="card shadow-sm border-0 rounded-lg">
      <div class="card-header bg-gradient-primary-to-secondary py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-white">Department List</h6>
        <button type="button" class="btn btn-light btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
          <i class="fas fa-plus me-1"></i> Add Department
        </button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="departmentsTable" width="100%" cellspacing="0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">ID</th>
                <th>Department</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($departments as $dept): ?>
              <tr>
                <td class="ps-4"><?= $dept['id'] ?></td>
                <td><?= $dept['department'] ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-start edit-dept-btn" 
                            data-id="<?= $dept['id'] ?>" 
                            data-name="<?= $dept['department'] ?>">
                      <i class="fas fa-edit"></i>
                    </button>
                    <a href="/setting/department/delete/<?= $dept['id'] ?>" class="btn btn-sm btn-outline-danger rounded-end delete-dept-btn">
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

    
<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/setting/department/add" method="POST" id="addDeptForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="addDepartmentModalLabel">Add Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="department_input" class="form-label">Department <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="department_input" name="department" required>
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

<!-- Edit Department Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/setting/department/update" method="POST" id="editDeptForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="editDepartmentModalLabel">Edit Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_dept_id">
          <div class="mb-3">
            <label for="edit_department_input" class="form-label">Department <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_department_input" name="department" required>
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
</div>
