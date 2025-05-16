  
    <!-- Locations Tab -->
  
  <div class="tab-pane fade" id="locations" role="tabpanel" aria-labelledby="locations-tab">
    <div class="card shadow-sm border-0 rounded-lg">
      <div class="card-header bg-gradient-primary-to-secondary py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-white">Location List</h6>
        <button type="button" class="btn btn-light btn-sm rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addLocationModal">
          <i class="fas fa-plus me-1"></i> Add Location
        </button>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="locationsTable" width="100%" cellspacing="0">
            <thead class="bg-light">
              <tr>
                <th class="ps-4">ID</th>
                <th>Location</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($locations as $loc): ?>
              <tr>
                <td class="ps-4"><?= $loc['id'] ?></td>
                <td><?= $loc['location'] ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-start edit-loc-btn" 
                            data-id="<?= $loc['id'] ?>" 
                            data-name="<?= $loc['location'] ?>">
                      <i class="fas fa-edit"></i>
                    </button>
                    <a href="/setting/location/delete/<?= $loc['id'] ?>" class="btn btn-sm btn-outline-danger rounded-end delete-loc-btn">
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
</div>

<!-- Add Location Modal -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/setting/location/add" method="POST" id="addLocForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="addLocationModalLabel">Add Location</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="location_input" class="form-label">Location <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="location_input" name="location" required>
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

<!-- Edit Location Modal -->
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="/setting/location/update" method="POST" id="editLocForm" class="needs-validation" novalidate>
        <div class="modal-header bg-gradient-primary">
          <h5 class="modal-title text-white" id="editLocationModalLabel">Edit Location</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_loc_id">
          <div class="mb-3">
            <label for="edit_location_input" class="form-label">Location <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_location_input" name="location" required>
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