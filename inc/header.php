<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');

$contact_q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
$values = [1];
$contact_r = mysqli_fetch_assoc(select($contact_q, $values, 'i'));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMS Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-2 shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold me-5 fs-3 h-font" href="index.php">PMS HOTEL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="rooms.php">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="facilities.php">Facilities</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                </ul>


                <!-- <div class="d-flex">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" type="button">Profile</button></li>
                                <li><button class="dropdown-item" type="button">Booking</button></li>
                                <form id="logoutForm" action="logout_handler.php" method="POST">
                                    <li><button class="dropdown-item" type="submit">Logout</button></li>
                                </form>
                            </ul>
                        </div>
                    </div> -->

                <?php
                if (isset($_SESSION['user']) && $_SESSION['user'] != null)  {
                    // User is logged in, show the profile and logout options
                    echo '<div class="d-flex">
            <div class="btn-group">
                <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><button class="dropdown-item" type="button">Profile</button></li>
                    <li><button class="dropdown-item" type="button">Booking</button></li>
                    <form id="logoutForm" action="logout_handler.php" method="POST">
                        <li><button class="dropdown-item" type="submit">Logout</button></li>
                    </form>
                </ul>
            </div>
        </div>';
                } else {
                    // User is not logged in, show the login and register buttons
                    echo '<div class="d-flex">
            <a href="./login/index.php" class="btn btn-outline-dark shadow-none me-lg-3 me-2">
                Login
            </a>
            <a href="./login/register.php" class="btn btn-outline-dark shadow-none me-lg-2 me-3">
                Register
            </a>
        </div>';
                }
                ?>


            </div>
        </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


<script>
    $(document).ready(function() {
        $("#logoutForm").on("submit", function(e) {
            e.preventDefault(); // Prevent default form submission

            Swal.fire({
                title: "Are you sure?",
                text: "Do you really want to logout?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, logout",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let formUrl = $(this).attr("action"); // Form action URL
                    let formData = $(this).serialize(); // Serialized form data

                    $.ajax({
                        url: formUrl,
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.status === "success") {
                                Swal.fire("Logged out!", response.msg, "success").then(() => {
                                    window.location.href = "Homepage.php"; // Redirect after success
                                });
                            } else {
                                Swal.fire("Error!", response.msg, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error!", "An error occurred during logout.", "error");
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire("Cancelled", "You are still logged in!", "info");
                }
            });
        });
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>