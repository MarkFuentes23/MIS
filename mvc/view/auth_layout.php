<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Authentication</title>
    <!-- Maaari kang magdagdag ng link sa CSS o iba pang meta tags dito para sa authentication pages -->
    <link rel="stylesheet" href="/path/to/your/auth-styles.css">
</head>
<body>
    <?php 
    // Dito natin i-include ang body content mula sa kung ano ang itinakdang view (login o register)
    if (isset($content_view)) {
        include_once $content_view;
    }
    ?>
</body>
</html>
