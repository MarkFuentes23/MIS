<?php include 'partials/header.php'; ?>
<?php include 'partials/sidebar.php'; ?>
<div class="main-content">
  <div class="container">
    <h1>View Employees</h1>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Middle Name</th>
          <th>Suffix</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($employees as $employee): ?>
          <tr onclick="window.location='/employee/view/<?php echo $employee['id']; ?>'" style="cursor:pointer;">
            <td><?php echo $employee['id']; ?></td>
            <td><?php echo $employee['firstname']; ?></td>
            <td><?php echo $employee['lastname']; ?></td>
            <td><?php echo $employee['middlename']; ?></td>
            <td><?php echo $employee['suffix']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'partials/footer.php'; ?>
