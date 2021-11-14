<?php

session_start();
//unset all of the session var (set them to empty array)
$_SESSION = array();

//destroy the session
session_destroy();

//redirect to.. home page?
header("location: login.php");
exit;
?>

<html>

</html>
