<h2>Login</h2>
<?php if(isset($data) && $data): ?>
    <p style="color:red;"><?php echo $data; ?></p>
<?php endif; ?>
<form method="post" action="/auth/login">
    <label>Username:</label>
    <input type="text" name="username" required><br/>
    <label>Password:</label>
    <input type="password" name="password" required><br/>
    <button type="submit">Login</button>
</form>
<p>
    Don't have an account? <a href="/auth/register">Register here</a>
</p>
