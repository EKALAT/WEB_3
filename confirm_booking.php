<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php') ?>
    <title>PMS HOTEL - CONFIRM BOOKING</title>

    <link rel="stylesheet" href="css/confirm_booking.css">
</head>

<body class="bg-light">

    <?php
    require('inc/header.php');

    // Use the filteration function to sanitize and get data from the URL
    $data = filteration($_GET);

    // Check if the room exists in the database
    $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii');

    if (mysqli_num_rows($room_res) == 0) {
        echo "Room not found.";
    } else {
        $room_data = mysqli_fetch_assoc($room_res);
        $_SESSION['room'] = [
            "id" => $room_data['id'],
            "name" => $room_data['name'],
            "price" => $room_data['price'],
            "payment" => null,
            "available" => false,
        ];
    }

    ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-12">
                <h2 class="fw-bold h-font">CONFIRM BOOKING</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">CONFIRM</a>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 px-12">
                <?php
                // Set room thumbnail image
                $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
                $thumb_q = mysqli_query($con, "SELECT * FROM `room_images` 
                WHERE `room_id`='$room_data[id]'
                AND `thumb`='1'");

                if (mysqli_num_rows($thumb_q) > 0) {
                    $thumb_res = mysqli_fetch_assoc($thumb_q);
                    $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
                }

                echo <<<data
                <div class="card p-3 shadow-sm rounded">
                <img src="$room_thumb" class="img-fluid rounded mb-3">
                <h5>$room_data[name]</h5>
                <h6>$room_data[price]$ per night</h6>
                </div>
                data;
                ?>
            </div>

            <div class="col-lg-4 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="#" id="booking_form">
                            <h3 class="mb-3">BOOKING DETAILS</h3>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user'] : ''; ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input name="phone" type="number" value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="location" class="form-control shadow-none" rows="1" required><?php echo isset($_SESSION['location']) ? $_SESSION['location'] : ''; ?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Check-in</label>
                                    <input name="checkin" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Check-out</label>
                                    <input name="checkout" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                                </div>
                                <div class="col-12">
                                    <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <h6 class="mb-3 text-danger" id="pay_info">Provide check-in & check-out date!</h6>
                                    <button name="book_now" class="btn btn-primary w-100 mb-1" disabled>Book now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="container">
                <div class="row">
                    <div class="col-12 my-5 mb-1 px-12">
                        <h2 class="fw-bold h-font">Review rooms</h2>
                    </div>
                </div>
            </div>

            <!-- Move the Review Section to the bottom -->
            <div class="col-12 mt-4 px-4">
                <div class="card mb-4 shadow rounded-3">
                    <div class="card-body">
                        <?php
                        // Get reviews from the database
                        $reviews_res = select("SELECT * FROM `reviews` WHERE `room_id`=?", [$room_data['id']], 'i');
                        if (mysqli_num_rows($reviews_res) > 0) {
                            while ($review = mysqli_fetch_assoc($reviews_res)) {
                                $rating = str_repeat("<span class='star-rating'>★</span>", $review['rating']) .
                                    str_repeat("<span class='star-rating'>☆</span>", 5 - $review['rating']);
                                // Format the timestamp
                                $created_at = date('F j, Y, g:i a', strtotime($review['created_at'])); // Example: "November 28, 2024, 4:30 pm"

                                echo "<div class='review-container mb-4'>
            <div class='review-header'>
                <h3><i class='bi bi-person-circle me-2'></i>" . $review['name'] . "</h3>
                <div class='rating-container'>" . $rating . "</div> <!-- Star rating here -->
            </div>
            <p>" . $review['review'] . "</p>
            <small class='text-muted'>Reviewed on: $created_at</small>
      </div>";
                            }
                        } else {
                            echo "<p>No reviews yet.</p>";
                        }
                        ?>

                    </div>
                </div>

                <!-- Review Form Section -->
                <div class="card mb-4 border shadow-sm rounded-3">
                    <div class="card-body">
                        <h3 class="mb-3 h-font">Leave a Review</h3>
                        <form action="submit_review.php" method="POST">
                            <input type="hidden" name="room_id" value="<?php echo $room_data['id']; ?>">
                            <div class="mb-3">
                                <label class="form-label " style="font-size: 25px;">Your Review</label>
                                <textarea name="review" class="form-control shadow-none" rows="1" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 25px;">Rating</label>
                                <div class="star-rating" id="star-rating">
                                    <span class="star inactive" data-rating="1">★</span>
                                    <span class="star inactive" data-rating="2">★</span>
                                    <span class="star inactive" data-rating="3">★</span>
                                    <span class="star inactive" data-rating="4">★</span>
                                    <span class="star inactive" data-rating="5">★</span>
                                </div>
                                <input type="hidden" name="rating" id="rating" value="">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success w-100 mb-1">Submit Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Booking Form Calculation Functionality
        let booking_form = document.getElementById('booking_form');
        let info_loader = document.getElementById('info_loader');
        let pay_info = document.getElementById('pay_info');

        function check_availability() {
            let checkin_val = booking_form.elements['checkin'].value;
            let checkout_val = booking_form.elements['checkout'].value;

            booking_form.elements['book_now'].setAttribute('disabled', true);

            if (checkin_val != '' && checkout_val != '') {
                pay_info.classList.add('d-none');
                pay_info.classList.replace('text-dark', 'text-danger');
                info_loader.classList.remove('d-none');

                let data = new FormData();
                data.append('check_availability', '');
                data.append('check_in', checkin_val);
                data.append('check_out', checkout_val);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/confirm_booking.php", true);

                xhr.onload = function() {
                    let data = JSON.parse(this.responseText);
                    if (data.status == 'check_in_out_equal') {
                        pay_info.innerText = "You cannot check-out on the same day!";
                    } else if (data.status == 'check_out_earlier') {
                        pay_info.innerText = "Checkout date is earlier than check-in date!";
                    } else if (data.status == 'check_in_earlier') {
                        pay_info.innerText = "Check-in date is earlier than today's date!";
                    } else if (data.status == 'unavailable') {
                        pay_info.innerText = "Room not available for this check-in date!";
                    } else {
                        pay_info.innerHTML = "No. of Days: " + data.days + "<br>Total Amount to Pay: " + data.payment + "$";
                        pay_info.classList.replace('text-danger', 'text-dark');
                        booking_form.elements['book_now'].removeAttribute('disabled');
                    }

                    pay_info.classList.remove('d-none');
                    info_loader.classList.add('d-none');
                }

                xhr.send(data);
            }
        }
        // Clickable Star Rating Functionality
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');

        // Set initial star rating state to inactive
        stars.forEach(star => {
            star.classList.add('inactive'); // All stars start as inactive (grey)
        });

        // Event listener for each star to handle click functionality
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;

                stars.forEach(star => {
                    if (star.getAttribute('data-rating') <= rating) {
                        star.classList.remove('inactive'); // Active stars (yellow)
                    } else {
                        star.classList.add('inactive'); // Inactive stars (grey)
                    }
                });
            });
        });
    </script>
    <?php require('inc/footer.php'); ?>

</body>

</html>