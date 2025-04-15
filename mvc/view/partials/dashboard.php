<?php include 'partials/header.php'; ?>
<?php include 'partials/sidebar.php'; ?>
<div class="main-content">
  <div class="container">
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo isset($user['username']) ? $user['username'] : 'User'; ?>!</p>
  </div>
</div>
<?php include 'partials/footer.php'; ?>
