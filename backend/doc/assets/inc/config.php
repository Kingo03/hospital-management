<?php
$dbuser = "root";
$dbpass = "";  // If your root password is empty
$host = "localhost";
$db = "hmisphp"; // Make sure this is the correct database name

// Establish MySQL connection
$mysqli = new mysqli($host, $dbuser, $dbpass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
