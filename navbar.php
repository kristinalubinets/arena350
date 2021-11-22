<?php
    session_start();
?>

<nav class="topnav">
    <a class="active" href="/">Home</a>
    <a href="/login.php">IDK</a>
    <a href="#">Profile</a>
    <a href="#">About</a>
    <?php if (!empty($_SESSION["loggedin"])) : ?>
        <a href="logout.php" class="btn logout">Sign Out</a>
    <?php endif; ?>
</nav>
