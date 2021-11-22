<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = "project";
mysqli_report(MYSQLI_REPORT_STRICT);
$conn = mysqli_connect($servername, $username, $password, "$dbname");
if (!$conn) {
    die('Could not Connect MySql Server:' . mysqli_connect_error());
}
?>