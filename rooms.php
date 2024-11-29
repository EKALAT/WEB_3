<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMS HOTEL - ROOMS</title>
    <link href="https://fonts.googleapis.com/css2?family=New+Amsterdam&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/common.css">
    <link rel="icon" href="./images/iconweb/laos.png" type="image/png">

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
    session_start();

    isset($_SESSION['user']) ? require('inc/header.php') : require('inc/header1.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
        <div class="h-line bg-dark"></div>

    </div>

    <div class="container-fluid">
        <div class="row">

            <div class="col px-4">


                <?php

                $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=?", [1, 0], 'ii');

                while ($room_data = mysqli_fetch_assoc($room_res)) {

                    // get features of room

                    $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
                INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
                WHERE rfea.room_id ='$room_data[id]'");


                    $features_data = "";
                    while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                        $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap t-font me-1 mb-1'>
                                $fea_row[name]
                                </span>";
                    }

                    // get facilities of room

                    $fac_q =  mysqli_query($con, "SELECT f.name FROM `facilities` f
                 INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id
                 WHERE rfac.room_id = '$room_data[id]'");

                    $facilities_data = "";
                    while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                        $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap t-font me-1 mb-1'>
                                $fac_row[name]
                                </span>";
                    }

                    // get thumbnail of image

                    $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
                    $thumb_q = mysqli_query($con, "SELECT * FROM `room_images` 
                WHERE `room_id`='$room_data[id]'
                AND `thumb`='1'");

                    if (mysqli_num_rows($thumb_q) > 0) {
                        $thumb_res = mysqli_fetch_assoc($thumb_q);
                        $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
                    }

                    // print room card
                    $_Booking = isset($_SESSION['user'])
                        ? "href='confirm_booking.php?id={$room_data['id']}'"
                        : " onclick=alert_(\"./login/index.php\")";

                    echo <<<data

                    <div class="card mb-4 border-0 shadow">
                        <div class="row g-0 p-3 align-items-center">
                            <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                                <img src="$room_thumb" class="img-fluid rounded">
                            </div>
                            <div class="col-md-5 px-lg-3 px-md-3 px-0">
                                <h5 class="mb-3">$room_data[name]</h5>
                                <div class="features mb-1">
                                    <h6 class="mb-1">Features</h6>
                                    $features_data
                                </div>
                                <div class="facilities mb-1">
                                    <h6 class="mb-1">Facilities</h6>
                                    $facilities_data
                                </div>
                                <div class="guests">
                                    <h6 class="mb-1">Guests</h6>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap t-font">
                                        $room_data[adult] Adults
                                    </span>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap t-font">
                                        $room_data[children] Children
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
                                <h6 class="mb-4">$room_data[price]$ per night</h6>
                                <a $_Booking class="Book btn-sm w-100 text-white submit-bg shadow-none t-font mb-2">Book Now</a>
                                <a $_Booking class="btn btn-sm w-100 btn-outline-dark shadow-none t-font">More details</a>
                            </div>
                        </div>
                    </div>

                data;
                }

                ?>



            </div>

        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./inc/alert.js"></script>

</body>

</html>