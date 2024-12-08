<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();

// Handle the delete request for booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking_id'])) {
    $bookingId = $_POST['delete_booking_id'];

    // Validate the booking ID (ensure it's an integer)
    if (filter_var($bookingId, FILTER_VALIDATE_INT) === false) {
        echo "error";  // Invalid booking ID
        exit;
    }

    // Call the function to delete the booking
    if (deleteBooking($bookingId)) {
        echo "success";  // Return success message
    } else {
        echo "error";  // Return error message
    }
    exit;
}

// Function to delete a booking from the 'booking_room' table
function deleteBooking($bookingId) {
    global $con;  // Access the global database connection variable

    // Prepare the DELETE query to remove a booking based on its ID
    $query = "DELETE FROM booking_room WHERE id = ?";
    $stmt = $con->prepare($query);  // Prepare the query statement

    // Check if the query was prepared successfully
    if (!$stmt) {
        error_log('SQL error: ' . $con->error);  // Log error if there's a preparation issue
        return false;  // Return false to indicate failure
    }

    // Bind the booking ID parameter to the query
    $stmt->bind_param('i', $bookingId);  // 'i' specifies that it's an integer

    // Execute the delete query
    if ($stmt->execute()) {
        return true;  // Return true to indicate successful deletion
    } else {
        error_log('Delete query failed: ' . $stmt->error);  // Log SQL error if the query fails
        return false;  // Return false if there was an issue executing the query
    }
}

// Fetch bookings for the logged-in user
$query = "SELECT br.id, br.room_id, r.name AS room_name, br.check_in, br.check_out, br.total_price, br.time_book, ri.image AS room_image
FROM booking_room br
INNER JOIN rooms r ON br.room_id = r.id
LEFT JOIN room_images ri ON r.id = ri.room_id
ORDER BY br.time_book DESC;";

$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
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
            // Show a confirmation dialog using SweetAlert2
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a new FormData object to send the booking ID
                    let data = new FormData();
                    data.append('delete_booking_id', bookingId); // Add the booking ID to the request

                    // Create an XMLHttpRequest to send the POST request
                    let xhr = new XMLHttpRequest();
                    xhr.open("POST", "bookings.php", true);

                    // Set up the callback to handle the response
                    xhr.onload = function() {
                        if (this.status === 200) {
                            let response = this.responseText;

                            // Log the response to debug
                            console.log('Server Response:', response);

                            if (response == 'success') {
                                // If the booking was successfully deleted, show a success message and reload the page
                                Swal.fire(
                                    'Deleted!',
                                    'Your booking has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload(); // Reload the page to reflect the changes
                                });
                            } else {
                                // If there was an error deleting the booking, show an error message
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting your booking. Please try again later.',
                                    'error'
                                );
                            }
                        } else {
                            // Handle case where the request didn't succeed
                            Swal.fire(
                                'Error!',
                                'Server error. Please try again later.',
                                'error'
                            );
                        }
                    };

                    // Send the request
                    xhr.send(data);
                }
            });
        }
    </script>
</body>

</html>
