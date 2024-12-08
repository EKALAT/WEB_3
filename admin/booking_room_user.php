<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();

// Fetch all bookings with user details
$query = "SELECT br.id, br.room_id, r.name AS room_name, br.check_in, br.check_out, br.total_price, br.time_book, ri.image AS room_image, u.name AS user_name
FROM booking_room br
INNER JOIN rooms r ON br.room_id = r.id
LEFT JOIN room_images ri ON r.id = ri.room_id
INNER JOIN users u ON br.user_id = u.id
ORDER BY br.time_book DESC;";

// Prepare and execute the query
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
        /* Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
        }

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
            padding: 10px 15px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .no-bookings {
            font-size: 1.2rem;
            color: #888;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php require('inc/header.php'); ?>

    <div class="content">
        <h1 class="h-font">List Bookings</h1>

        <!-- Booking Table -->
        <?php if ($result->num_rows > 0) { ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="t-font">Room</th>
                        <th class="t-font">Name</th>
                        <th class="t-font">User</th>
                        <th class="t-font">Check-in</th>
                        <th class="t-font">Check-out</th>
                        <th class="t-font">Total Price</th>
                        <th class="t-font">Booking Time</th>
                        <th class="t-font">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><img src="../images/rooms/<?php echo $row['room_image']; ?>" alt="Room Image" width="100"></td>
                            <td><?php echo $row['room_name']; ?></td>
                            <td><?php echo $row['user_name']; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['check_in'])); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['check_out'])); ?></td>
                            <td><?php echo $row['total_price']; ?>$</td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['time_book'])); ?></td>
                            <td>
                                <button class="delete-btn" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="no-bookings">No bookings found.</p>
        <?php } ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(bookingId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform delete operation
                    fetch('delete_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `booking_id=${bookingId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Deleted!',
                                'Your booking has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            );
                        }
                    })
                    .catch(() => {
                        Swal.fire(
                            'Error!',
                            'Failed to delete the booking.',
                            'error'
                        );
                    });
                }
            });
        }
    </script>
    <?php require('inc/script.php'); ?>
</body>

</html>
