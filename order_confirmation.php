<?php
session_start();
$username = $_SESSION['username'];
?>

<html>
<head>
    <?php include("head.php") ?>
</head>
<?php include("navbar.php") ?>
<body>
<div class="container">
    <h2>Thank you for your order, <?=$username?>!</h2>
</div>
</body>
</html>

