  <div class="tab-pane fade" id="job-titles" role="tabpanel" aria-labelledby="job-titles-tab">
    <div class="card shadow-sm border-0 rounded-lg">
      <div class="card-header bg-gradient-primary-to-secondary py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-white">Job Title List</h6>
        <button type="button" class="btn btn-light btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addJobTitleModal">
          <i class="fas fa-plus me-1"></i> Add Job Title
        </button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="jobTitlesTable" width="100%" cellspacing="0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">ID</th>
                <th>Job Title</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($job_titles as $job): ?>
              <tr>
                <td class="ps-4"><?= $job['id'] ?></td>
                <td><?= $job['job_title'] ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-start edit-job-btn" 
                            data-id="<?= $job['id'] ?>" 
                            data-title="<?= $job['job_title'] ?>">
                      <i class="fas fa-edit"></i>
                    </button>
                    <a href="/setting/jobTitle/delete/<?= $job['id'] ?>" class="btn btn-sm btn-outline-danger rounded-end delete-job-btn">
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

<!-- Add Job Title Modal -->
  <div class="modal fade" id="addJobTitleModal" tabindex="-1" aria-labelledby="addJobTitleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/setting/jobTitle/add" method="POST" id="addJobForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="addJobTitleModalLabel">Add Job Title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="job_title_input" class="form-label">Job Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="job_title_input" name="job_title" required>
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

<!-- Edit Job Title Modal -->
<div class="modal fade" id="editJobTitleModal" tabindex="-1" aria-labelledby="editJobTitleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/setting/jobTitle/update" method="POST" id="editJobForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="editJobTitleModalLabel">Edit Job Title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_job_id">
          <div class="mb-3">
            <label for="edit_job_title_input" class="form-label">Job Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_job_title_input" name="job_title" required>
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