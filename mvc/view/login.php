<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if(isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="post" action="/auth/login">
        <label>Username:</label>
        <input type="text" name="username" required><br/>
        <label>Password:</label>
        <input type="password" name="password" required><br/>
        <button type="submit">Login</button>
    </form>
</body>
</html>
