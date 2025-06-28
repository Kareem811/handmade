<?php
// 404.php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 - Page Not Found</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
            color: #333;
            text-align: center;
        }

        .error-code {
            font-size: 10rem;
            font-weight: bold;
            color: #ff6b6b;
            text-shadow: 2px 2px #ddd;
        }

        .error-message {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .home-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #ff6b6b;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .home-button:hover {
            background-color: #e74c3c;
        }
    </style>
</head>

<body>
    <div class="error-code">404</div>
    <div class="error-message">Oops! The page you’re looking for doesn’t exist.</div>
    <a href="index.php" class="home-button">Go Home</a>
</body>

</html>