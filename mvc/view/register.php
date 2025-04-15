<h2>Register</h2>
<?php if(isset($data) && $data): ?>
    <p style="color:red;"><?php echo $data; ?></p>
<?php endif; ?>
<form method="post" action="/auth/register">
    <label>Username:</label>
    <input type="text" name="username" required><br/>
    <label>Password:</label>
    <input type="password" name="password" required><br/>
    <button type="submit">Register</button>
</form>
<p>
    Already have an account? <a href="/auth/login">Login here</a>
</p>
