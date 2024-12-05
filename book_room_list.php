<?php
session_start();
require('admin/inc/db_config.php');

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("Error: Please log in to view your bookings.");
}

$user_id = $_SESSION['id'];

// Fetch bookings for the logged-in user
$query = "SELECT br.id, br.room_id, r.name AS room_name, br.check_in, br.check_out, br.total_price, br.time_book
          FROM booking_room br
          INNER JOIN rooms r ON br.room_id = r.id
          WHERE br.user_id = ? ORDER BY br.time_book DESC";

$stmt = $con->prepare($query);
$stmt->bind_param('i', $user_id);
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
        /* Basic Container */
        .container {
            width: 90%; /* Increased width for the container */
            margin: 0 auto;
            padding: 20px;
        }

        /* Table Styling */
        table {
            width: 100%; /* Full width of the container */
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 15px; /* Increased padding for larger cells */
            text-align: left;
            font-size: 1.1rem; /* Increased font size for better readability */
        }

        th {
            background-color: #343a40; /* Dark Background */
            color: white; /* White Text */
        }

        tr:hover {
            background-color: #f1f1f1; /* Light Gray on Hover */
        }

        /* No Bookings Message */
        .no-bookings {
            text-align: center;
            font-size: 1.2rem;
            color: #888;
        }
    </style>
</head>

<body>
    <?php require('inc/header.php'); ?>
    <div class="container">
        <h1 class="h-font">My Bookings</h1>
        <?php if ($result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th class="t-font">#</th>
                        <th class="t-font">Room Name</th>
                        <th class="t-font">Check-in</th>
                        <th class="t-font">Check-out</th>
                        <th class="t-font">Total Price</th>
                        <th class="t-font">Booking Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // Initialize counter
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $counter++; ?></td> <!-- Display sequential numbers -->
                            <td><?php echo $row['room_name']; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['check_in'])); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['check_out'])); ?></td>
                            <td><?php echo $row['total_price']; ?>$</td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['time_book'])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="no-bookings">No bookings found.</p>
        <?php } ?>
    </div>
    <?php require('inc/footer.php'); ?>
</body>

</html>
