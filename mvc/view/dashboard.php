<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($data['username']); ?></h2>
    <p>You are now logged in.</p>
    <p><a href="/auth/logout">Logout</a></p>
</body>
</html>
