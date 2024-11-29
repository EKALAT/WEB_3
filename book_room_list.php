<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php') ?> <!-- Include your links for CSS and other assets -->
    <title>PMS HOTEL - BOOKING HISTORY</title>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

    <style>
        .pop:hover {
            border-top-color: var(--teal) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }
    </style>
</head>

<body class="bg-light">

    <?php
    // Include header (you have it in the 'inc' folder already)
    require('inc/header.php');
    ?>

    <!-- Main Content Area -->
    <div class="container-fluid custom-container">
        <!-- Breadcrumb -->
        <p class="mt-1" style="color: grey;">
            <a style="text-decoration: none; color: grey;" href="index.php">Home</a> > Booking History
        </p>

        <!-- Booking History Table -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Room</th>
                    <th>Name</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Booking Time</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get the logged-in user's username from the cookie
                $username = $_COOKIE['username'];

                // Updated SQL query to fetch booking history from the 'booking_room' table
                $sql_get_booking_room_history = "SELECT * FROM booking_room, rooms WHERE booking_room.username = '$username' 
                                                 AND booking_room.room_id = rooms.room_id ORDER BY booking_room.time_book DESC";
                $result_get_room_history = $conn->query($sql_get_booking_room_history);
                $i = 0;

                // Loop through the results and display each booking
                while ($row = $result_get_room_history->fetch_assoc()) {
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><img class="rounded" width="100px" src="<?php echo $row['room_image']; ?>" alt="Room Image"></td>
                        <td><?php echo $row['room_name']; ?></td>
                        <td><?php echo $row['check_in']; ?></td>
                        <td><?php echo $row['check_out']; ?></td>
                        <td><?php echo $row['time_book']; ?></td>
                        <td>Total Days: <?php echo $row['total_day']; ?> <br> 
                            Total Price: <?php echo $row['total_price'] . '$'; ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
    <?php
    // Ensure footer is included here and not before the closing body tag
    require('inc/footer.php');
    ?>

</body>

</html>
