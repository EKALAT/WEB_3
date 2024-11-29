<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "hbwebsite");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
