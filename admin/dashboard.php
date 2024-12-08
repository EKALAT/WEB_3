<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();

// Fetch total number of users
$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $con->query($user_query);
$total_users = $user_result->fetch_assoc()['total_users'];

// Fetch total number of bookings
$booking_query = "SELECT COUNT(*) AS total_bookings FROM booking_room";
$booking_result = $con->query($booking_query);
$total_bookings = $booking_result->fetch_assoc()['total_bookings'];

// Fetch total number of rooms
$room_count_query = "SELECT COUNT(*) AS total_rooms FROM rooms";
$room_count_result = $con->query($room_count_query);
$total_rooms = $room_count_result->fetch_assoc()['total_rooms'];

// Fetch total monthly profit (sum of total_price for all bookings this month)
$monthly_profit_query = "
    SELECT SUM(total_price) AS total_profit
    FROM booking_room
    WHERE time_book >= CURDATE() - INTERVAL 1 MONTH
";
$monthly_profit_result = $con->query($monthly_profit_query);
$monthly_profit = $monthly_profit_result->fetch_assoc()['total_profit'] ?? 0;  // Default to 0 if no results

// Fetch total number of reviews
$review_query = "SELECT COUNT(*) AS total_reviews FROM reviews";
$review_result = $con->query($review_query);
$total_reviews = $review_result->fetch_assoc()['total_reviews'];

// Fetch total number of contacts from user_queries
$contact_query = "SELECT COUNT(*) AS total_contacts FROM user_queries";
$contact_result = $con->query($contact_query);
$total_contacts = $contact_result->fetch_assoc()['total_contacts'];

// Fetch total number of team members from team_details
$team_query = "SELECT COUNT(*) AS total_team_members FROM team_details";
$team_result = $con->query($team_query);
$total_team_members = $team_result->fetch_assoc()['total_team_members'];

// Fetch total number of features from features table
$features_query = "SELECT COUNT(*) AS total_features FROM features";
$features_result = $con->query($features_query);
$total_features = $features_result->fetch_assoc()['total_features'];

// Fetch total number of facilities from facilities table
$facilities_query = "SELECT COUNT(*) AS total_facilities FROM facilities";
$facilities_result = $con->query($facilities_query);
$total_facilities = $facilities_result->fetch_assoc()['total_facilities'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php require('inc/links.php'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .dashboard-card {
            background-color: #007bff;
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .card-icon {
            font-size: 60px;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-body p {
            font-size: 1.1rem;
        }

        .profit-card {
            background-color: #28a745;
        }

        .contact-card {
            background-color: #17a2b8;
        }

        .team-card {
            background-color: #6f42c1;
        }

        .feature-facility-card {
            background-color: red;
        }

        /* Sidebar Styles */
        .sidebar {
            height: 100%;
            background-color: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background-color: #575d63;
        }

        /* Top Nav Styles */
        .top-nav {
            background-color: #343a40;
            color: white;
            padding: 10px 20px;
        }

        /* Content Padding */
        .content {
            margin-left: 260px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: static;
                height: auto;
            }

            .top-nav {
                padding: 15px;
            }

            .content {
                margin-left: 0;
            }
        }

        .feature-facility-text {
            margin-bottom: 5px;
            /* Adjust this value to control the space between the two texts */
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>
    <!-- Main Content -->
    <div class="content">

        <!-- Dashboard Cards -->
        <div class="container-fluid">
            <div class="row">
                <!-- Total Users Card -->
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fas fa-user-friends card-icon"></i>
                        <h5 class="card-title h-font">Total Users</h5>
                        <p><?= $total_users ?> Users</p>
                    </div>
                </div>

                <!-- Total Bookings Card -->
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fas fa-calendar-check card-icon"></i>
                        <h5 class="card-title h-font">Total Bookings</h5>
                        <p><?= $total_bookings ?> Bookings</p>
                    </div>
                </div>

                <!-- Total Rooms Card -->
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <i class="fas fa-hotel card-icon"></i>
                        <h5 class="card-title h-font">Total Rooms</h5>
                        <p><?= $total_rooms ?> Rooms</p>
                    </div>
                </div>

                <!-- Monthly Profit Card -->
                <div class="col-md-3">
                    <div class="dashboard-card profit-card">
                        <h5 class="card-title h-font">Total Profit<br>(This Month)</h5>
                        <p style="font-size: 2rem;">$<?= number_format($monthly_profit, 2) ?></p>
                    </div>
                </div>

                <!-- Total Reviews Card -->
                <div class="col-md-3">
                    <div class="dashboard-card" style="background-color: #ffc107;">
                        <i class="fas fa-star card-icon"></i>
                        <h5 class="card-title h-font">Total Reviews</h5>
                        <p><?= $total_reviews ?> Reviews</p>
                    </div>
                </div>

                <!-- Total Contacts Card -->
                <div class="col-md-3">
                    <div class="dashboard-card contact-card">
                        <i class="fas fa-envelope card-icon"></i>
                        <h5 class="card-title h-font">Total Contacts</h5>
                        <p><?= $total_contacts ?> Contacts</p>
                    </div>
                </div>

                <!-- Total Team Members Card -->
                <div class="col-md-3">
                    <div class="dashboard-card team-card">
                        <i class="fas fa-users card-icon"></i>
                        <h5 class="card-title h-font">Total Team Members</h5>
                        <p><?= $total_team_members ?> Members</p>
                    </div>
                </div>

                <!-- Total Features & Facilities Card -->
                <div class="col-md-3">
                    <div class="dashboard-card feature-facility-card">
                        <i class="fas fa-cogs card-icon"></i>
                        <h5 class="card-title h-font">Total Features & Facilities</h5>
                        <p style="margin-bottom: 2px;"><?= $total_features ?> Features</p>
                        <p style="margin-top: 0;"><?= $total_facilities ?> Facilities</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <?php require('inc/script.php'); ?>

</body>

</html>