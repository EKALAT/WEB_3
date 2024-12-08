<?php
session_start();
require('admin/inc/db_config.php');

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
WHERE br.user_id = ?
ORDER BY br.time_book DESC LIMIT 0, 25;
";

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
        /* Basic layout to ensure footer stays at the bottom */
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
        }

        body {
            flex-grow: 1;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
            flex-grow: 1; /* Make sure content takes all available space */
            box-sizing: border-box;
        }

        /* Table Styling */
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 30px;
            margin-left: auto;
            margin-right: auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
            font-size: 1.1rem;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* No Bookings Message */
        .no-bookings {
            text-align: center;
            font-size: 1.2rem;
            color: #888;
        }

        /* Pay Now Button */
        .pay-now-btn {
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background-color: lightseagreen;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .pay-now-btn:hover {
            background-color: red;
        }

        /* Footer Styling */
        footer {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1rem;
            width: 100%;
            position: relative;
            bottom: 0;
            margin-top: 30px;
            clear: both;
        }

        footer a {
            color: lightseagreen;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php require('inc/header.php'); ?>
    <div class="container">
        <h1 class="h-font">My Bookings</h1>
        <div style="font-size: 14px;">
            <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
            <span class="text-secondary"> &gt; </span>
            <a class="text-secondary text-decoration-none">My Booking</a>
            <span class="text-secondary"> &gt; </span>
        </div>

        <?php if ($result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th class="t-font">#</th>
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
                            <td>
                                <img src="./images/rooms/<?php echo $row['room_image'] ?>" alt="Room Image" width="100">
                            </td>
                            <td><?php echo $row['room_name']; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['check_in'])); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['check_out'])); ?></td>
                            <td><?php echo $row['total_price']; ?>$</td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['time_book'])); ?></td>
                            <td>
                                <!-- Pay Now Button -->
                                <button onclick="showPaymentAlert()" class="pay-now-btn t-font">Pay Now</button>
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
        function showPaymentAlert() {
            Swal.fire({
                icon: 'info',
                title: 'Sorry!',
                text: 'We do not accept online payments at the moment.',
                confirmButtonText: 'OK'
            });
        }
    </script>

    <?php require('inc/footer.php'); ?>
</body>

</html>
