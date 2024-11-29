<?php
session_start();
require('admin/inc/db_config.php'); // Include your database connection file

// Check if the review form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve data from the form
    $room_id = $_POST['room_id'];
    $review = mysqli_real_escape_string($con, $_POST['review']);
    $rating = $_POST['rating'];
    $user_name = isset($_SESSION['user']) ? $_SESSION['user'] : 'Guest';

    // Insert the review into the database with the current timestamp
    $query = "INSERT INTO `reviews` (`room_id`, `name`, `review`, `rating`, `created_at`) 
              VALUES ('$room_id', '$user_name', '$review', '$rating', NOW())";
    
    if (mysqli_query($con, $query)) {
        // Redirect to the same page after submission (to avoid resubmitting on refresh)
        header("Location: confirm_booking.php?id=$room_id");
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>