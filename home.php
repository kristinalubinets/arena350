<?php
    session_start(); // access the session
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
        }
    </style>
</head>
<body>
    <h1>
        Welcome back to the Arena,
        <?php echo htmlspecialchars($_SESSION["username"]); ?>
    </h1>
    <div>
        <a href="logout.php" class="btn btn-danger ml-3">Sing Out</a>
    </div>
</body>
</html>
