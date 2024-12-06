<?php
session_start();
require('./inc/db_config.php');

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("Error: Please log in to view your bookings.");
}

$user_id = $_SESSION['id'];

// Fetch bookings for the logged-in user
$query = "SELECT br.id, br.room_id, r.name AS room_name, br.check_in, br.check_out, br.total_price, br.time_book, ri.image AS room_image
FROM booking_room br
INNER JOIN rooms r ON br.room_id = r.id
LEFT JOIN room_images ri ON r.id = ri.room_id
ORDER BY br.time_book DESC;";

$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking_id'])) {
    $bookingId = $_POST['delete_booking_id'];

    // Validate the bookingId
    if (filter_var($bookingId, FILTER_VALIDATE_INT) === false) {
        echo "error";  // Invalid booking ID
        exit;
    }

    // Prepare and execute the delete query
    $deleteQuery = "DELETE FROM booking_room WHERE id = ?";
    $deleteStmt = $con->prepare($deleteQuery);
    $deleteStmt->bind_param('i', $bookingId);

    if ($deleteStmt->execute()) {
        echo "success";  // Return success message
    } else {
        echo "error";    // Return error message
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <?php require('inc/links.php'); ?>
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }

        .container {
            width: 85%;
            margin: 0 auto;
            padding: 20px;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .sidebar a {
            display: block;
            padding: 15px;
            color: #ecf0f1;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #3498db;
        }

        .sidebar a.active {
            background-color: #2980b9;
        }

        /* Content Area */
        .content {
            margin-left: 270px;
            padding: 30px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: black;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        tr:hover {
            background-color: #bdc3c7;
        }

        .delete-btn {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background-color: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .delete-btn i {
            margin-right: 5px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        /* No Bookings Message */
        .no-bookings {
            font-size: 1.2rem;
            color: #888;
            text-align: center;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>PMS Website</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="bookings.php">My Bookings</a>
        <a href="users.php" class="active">Users</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php">Log Out</a>
    </div>

    <!-- Content Area -->
    <div class="content">
        <h1 class="h-font">List Bookings</h1>

        <!-- Booking Table -->
        <?php if ($result->num_rows > 0) { ?>
            <table class="border text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="t-font">Room</th>
                        <th class="t-font">Name</th>
                        <th class="t-font">Check-in</th>
                        <th class="t-font">Check-out</th>
                        <th class="t-font">Total Price</th>
                        <th class="t-font">Booking Time</th>
                        <th class="t-font">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // Initialize counter
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><img src="../images/rooms/<?php echo $row['room_image'] ?>" alt="Room Image" width="100"></td>
                            <td><?php echo $row['room_name']; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['check_in'])); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['check_out'])); ?></td>
                            <td><?php echo $row['total_price']; ?>$</td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['time_book'])); ?></td>
                            <td>
                                <button class="delete-btn" onclick="deleteBooking(<?php echo $row['id']; ?>)">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="no-bookings">No bookings found.</p>
        <?php } ?>
    </div>

    <script>
        function deleteBooking(bookingId) {
            // Show a confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete the booking
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "bookings.php", true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (this.responseText == 'success') {
                            // If deletion is successful, show success message and reload the page
                            Swal.fire(
                                'Deleted!',
                                'Your booking has been deleted.',
                                'success'
                            ).then(() => {
                                location.reload(); // Reload the page to reflect changes
                            });
                        } else {
                            // If there is an error, show an error message
                            Swal.fire(
                                'Error!',
                                'There was an issue deleting your booking. Please try again later.',
                                'error'
                            );
                        }
                    };
                    // Send booking ID to delete
                    xhr.send('delete_booking_id=' + bookingId);
                }
            });
        }
    </script>
</body>

</html>
