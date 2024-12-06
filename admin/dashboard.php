<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and Container */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        .main-container {
            display: flex;
        }

        /* Left Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #fff;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #ddd;
            text-decoration: none;
            font-size: 1rem;
            display: block;
        }

        .sidebar ul li a:hover {
            color: white;
        }

        /* Content Area */
        .content {
            margin-left: 250px; /* Matches the width of the sidebar */
            padding: 20px;
            flex: 1;
        }

        .dashboard-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 280px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card h3 {
            font-size: 1.4rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1rem;
            color: #555;
        }

        .table-container {
            margin-top: 30px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead th {
            background-color: #007bff;
            color: white;
            padding: 12px 10px;
            text-align: left;
            font-size: 1rem;
        }

        table tbody td {
            padding: 12px 10px;
            font-size: 0.95rem;
            color: #333;
        }

        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                flex-wrap: wrap;
            }

            .card {
                flex: 1 1 100%;
            }

            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 200px;
            }
        }
    </style>
</head>
<body class="bg-light">
<?php require('inc/header.php'); ?>

<!-- Main Content -->
<div class="main-container">
    <div class="content">
        <div class="container mt-5">
            <!-- Dashboard Cards -->
            <div class="dashboard-cards mt-4">
                <!-- Total Users Card -->
                <div class="card">
                    <h3>Total Users</h3>
                    <p>Number of users: <?= $total_users ?></p>
                </div>

                <!-- Total Bookings Card -->
                <div class="card">
                    <h3>Total Bookings</h3>
                    <p>Number of bookings: <?= $total_bookings ?></p>
                </div>

                <!-- All Rooms Card -->
                <div class="card">
                    <h3>All Rooms</h3>
                    <p>View and manage rooms</p>
                    <button onclick="viewRooms()">View Rooms</button>
                </div>
            </div>

            <!-- Room Table Section -->
            <div class="table-container mt-5">
                <h3>All Rooms</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Room Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($room = $rooms_result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($room['name']) ?></td>
                                <td>$<?= htmlspecialchars($room['price']) ?></td>
                                <td><?= htmlspecialchars($room['description']) ?></td>
                                <td><button class="btn btn-primary">View Details</button></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Function to view rooms
    function viewRooms() {
        Swal.fire({
            title: 'View All Rooms',
            text: 'Here, you can see and manage all the rooms in the hotel.',
            icon: 'info',
            confirmButtonText: 'Close'
        });
    }
</script>
</body>
</html>
