<?php include 'partials/header.php'; ?>
<?php include 'partials/sidebar.php'; ?>
<div class="main-content">
  <div class="container">
    <h1>Add Employee</h1>
    <?php if(isset($error)) { echo '<div class="alert alert-danger">' . $error . '</div>'; } ?>
    <form action="/employee/add" method="post">
      <div class="form-group">
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" id="firstname" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" id="lastname" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="middlename">Middle Name</label>
        <input type="text" name="middlename" id="middlename" class="form-control">
      </div>
      <div class="form-group">
        <label for="suffix">Suffix</label>
        <input type="text" name="suffix" id="suffix" class="form-control">
      </div>
      <button type="submit" class="btn btn-success">Add Employee</button>
    </form>
  </div>
</div>
<?php include 'partials/footer.php'; ?>
