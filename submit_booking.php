<?php
session_start();
require('admin/inc/db_config.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_SESSION['room']['id']; // Room ID is predefined in your case, otherwise, this would come from the form
    $user_id = $_SESSION['id'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $price = $_SESSION['room']['payment']; // You can calculate this dynamically or fetch it from the room price

    // Check if the user is logged in
    if (!$user_id) {
        die("Error: User must be logged in to book a room.");
    }

    // Ensure the check-in and check-out dates are in the correct format
    if (!$checkin || !$checkout) {
        die("Error: Please provide both check-in and check-out dates.");
    }

    // Check if user exists in the users table
    $user_check_query = "SELECT id FROM users WHERE id = ?";
    $stmt = $con->prepare($user_check_query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $user_check_result = $stmt->get_result();

    if ($user_check_result->num_rows == 0) {
        die("Error: Invalid user. Please log in with a valid account.");
    }

    // Insert booking details into the database using prepared statements to prevent SQL injection
    $query = "INSERT INTO `booking_room` (`room_id`, `user_id`, `check_in`, `check_out`, `total_price`, `time_book`) 
              VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $con->prepare($query);
    $stmt->bind_param('iissd', $room_id, $user_id, $checkin, $checkout, $price);

    if ($stmt->execute()) {
        // Set session variables for success
        $_SESSION['booking_success'] = true;
        $_SESSION['booking_data'] = [
            'days' => (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24),
            'payment' => $price
        ];

        // Redirect to the same page to trigger SweetAlert on page load
        // header("Location: " . $_SERVER['PHP_SELF']);
        header("Location: confirm_booking.php?id=$room_id");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
