
<!-- KRA Tab -->
<div class="tab-pane fade" id="kra" role="tabpanel" aria-labelledby="kra-tab">
  <div class="card shadow-sm border-0 rounded-lg">
    <div class="card-header bg-gradient-primary-to-secondary py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-white">KRA List</h6>
      <button type="button" class="btn btn-light btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addKraModal">
        <i class="fas fa-plus me-1"></i> Add KRA
      </button>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="kraTable" width="100%" cellspacing="0">
          <thead class="bg-light">
            <tr>
              <th class="ps-4">ID</th>
              <th>KRA</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($kras as $kra): ?>
            <tr>
              <td class="ps-4"><?= $kra['id'] ?></td>
              <td><?= $kra['kra'] ?></td>
              <td class="text-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-primary rounded-start edit-kra-btn" 
                          data-id="<?= $kra['id'] ?>" 
                          data-name="<?= $kra['kra'] ?>">
                    <i class="fas fa-edit"></i>
                  </button>
                  <a href="/setting/kra/delete/<?= $kra['id'] ?>" class="btn btn-sm btn-outline-danger rounded-end delete-kra-btn">
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



<!-- Add KRA Modal -->
<div class="modal fade" id="addKraModal" tabindex="-1" aria-labelledby="addKraModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/setting/kra/add" method="POST" id="addKraForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="addKraModalLabel">Add KRA</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="kra_input" class="form-label">KRA <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="kra_input" name="kra" required>
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

<!-- Edit KRA Modal -->
<div class="modal fade" id="editKraModal" tabindex="-1" aria-labelledby="editKraModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/setting/kra/update" method="POST" id="editKraForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="editKraModalLabel">Edit KRA</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_kra_id">
          <div class="mb-3">
            <label for="edit_kra_input" class="form-label">KRA <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_kra_input" name="kra" required>
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

