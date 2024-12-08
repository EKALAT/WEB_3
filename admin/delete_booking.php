<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = $_POST['booking_id'];

    if (!filter_var($bookingId, FILTER_VALIDATE_INT)) {
        echo json_encode(['success' => false, 'message' => 'Invalid booking ID.']);
        exit;
    }

    $query = "DELETE FROM booking_room WHERE id = ?";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare query.']);
        exit;
    }

    $stmt->bind_param('i', $bookingId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete booking.']);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
?>
