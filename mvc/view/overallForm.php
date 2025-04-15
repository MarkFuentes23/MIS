<?php include 'partials/header.php'; ?>
<?php include 'partials/sidebar.php'; ?>
<div class="main-content">
  <div class="container">
    <h1>Evaluation Form</h1>
    <?php if(isset($error)) { echo '<div class="alert alert-danger">' . $error . '</div>'; } ?>
    <form action="/evaluation/save" method="post">
      <div class="form-group">
        <label for="employee_id">Name</label>
        <select name="employee_id" id="employee_id" class="form-control" required>
          <option value="">Select Employee</option>
          <?php foreach($employees as $emp): ?>
            <option value="<?php echo $emp['id']; ?>"><?php echo $emp['firstname'] . ' ' . $emp['lastname']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="form-group">
        <label for="position_title_id">Position Title</label>
        <select name="position_title_id" id="position_title_id" class="form-control" required>
          <option value="">Select Position</option>
          <?php foreach($jobTitles as $job): ?>
            <option value="<?php echo $job['id']; ?>"><?php echo $job['job_title']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="form-group">
        <label for="reviewer_id">Reviewer</label>
        <select name="reviewer_id" id="reviewer_id" class="form-control" required>
          <option value="">Select Reviewer</option>
          <?php foreach($employees as $emp): ?>
            <option value="<?php echo $emp['id']; ?>"><?php echo $emp['firstname'] . ' ' . $emp['lastname']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="form-group">
        <label for="evaluation_period">Evaluation Period</label>
        <input type="number" name="evaluation_period" id="evaluation_period" class="form-control" required>
      </div>
      
      <div class="form-group">
        <label for="department_id">Department</label>
        <select name="department_id" id="department_id" class="form-control" required>
          <option value="">Select Department</option>
          <?php foreach($departments as $dept): ?>
            <option value="<?php echo $dept['id']; ?>"><?php echo $dept['department']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="form-group">
        <label for="reviewer_designation_id">Reviewer Designation</label>
        <select name="reviewer_designation_id" id="reviewer_designation_id" class="form-control" required>
          <option value="">Select Reviewer Designation</option>
          <?php foreach($jobTitles as $job): ?>
            <option value="<?php echo $job['id']; ?>"><?php echo $job['job_title']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <button type="submit" class="btn btn-primary">Submit Evaluation</button>
    </form>
  </div>
</div>
<?php include 'partials/footer.php'; ?>
