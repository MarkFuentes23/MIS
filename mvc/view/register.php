<!-- mvc/view/register.php -->
<div class="container">
    <h2>Register</h2>
    <?php if(isset($data['error'])): ?>
        <div class="alert alert-danger"><?php echo $data['error']; ?></div>
    <?php endif; ?>
    <form method="post" action="/auth/register">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p class="mt-3">Already have an account? <a href="/auth/login">Login here</a></p>
</div>
