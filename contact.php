<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMS HOTEL - CONTACT</title>
    <?php require('inc/links.php') ?>
    <style>
        .pop:hover {
            border-top-color: var(--teal) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }

        /* Toast container for top-right position with slight adjustment */
        .toast-container {
            position: fixed;
            top: 5rem;
            right: 1rem;
            z-index: 1055;
        }

        .toast {
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
        }

        .toast.show {
            opacity: 1;
        }
    </style>
</head>

<body class="bg-light">

    <?php 
    session_start();    
    
    isset($_SESSION['user']) ? require('inc/header.php') : require('inc/header1.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas, molestiae odio. Nam error eum quidem! Doloremque, <br> laborum alias voluptatum magni quam sit facilis minus? Deleniti ullam voluptatibus totam iure unde.
        </p>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">

                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 mb-4" height="320px"
                        src="<?php echo $contact_r['iframe'] ?>"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                    <h5>Address</h5>
                    <a href="<?php echo $contact_r['gmap'] ?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i> <?php echo $contact_r['address'] ?>
                    </a>

                    <h5 class="mt-4">Call us</h5>
                    <a href="tel:+<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-inbound-fill"></i> +<?php echo $contact_r['pn1'] ?>
                    </a>
                    <br>

                    <?php
                    if ($contact_r['pn2'] != '') {
                        echo <<<data
                                <a href="tel: +$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark">
                                <i class="bi bi-telephone-inbound-fill"></i> +$contact_r[pn2]
                                </a>
                            data;
                    }
                    ?>

                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: <?php echo $contact_r['email'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark"><i class="bi bi-envelope-fill"></i> <?php echo $contact_r['email'] ?>
                    </a>

                    <h5 class="mt-4">Follow us</h5>
                    <?php
                    if ($contact_r['tw'] != '') {
                        echo <<<data
                            <a href="$contact_r[tw]" class="d-inline-block text-dark fs-5 me-2">
                                <i class="bi bi-twitter me-1"></i>
                            </a>
                            data;
                    }
                    ?>


                    <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark fs-5 me-2 ">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark fs-5 ">
                        <i class="bi bi-instagram me-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form method="POST">
                        <h5>Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Name</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Subject</label>
                            <input name="subject" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Message</label>
                            <textarea name="message" required class="form-control shadow-none" rows="5"
                                style="resize: none;"></textarea>
                        </div>
                        <button type="submit" name="send" class="btn btn-danger text-white custom-bg mt-3">SEND</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast container for alerts -->
    <div class="toast-container">
        <div id="toast-alert" class="toast align-items-center text-bg-success border-0 fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body t-font">
                    Mail sent successfully!
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <?php
    if(isset($_POST['send']))
    {
        $frm_data = filteration($_POST);

        $q = "INSERT INTO `user_queries`(`name`,`email`,`subject`,`message`) VALUE (?,?,?,?)";
        $values = [$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message']];

        $res = insert($q,$values,'ssss');
        if($res==1){
            echo "<script>
                const toast = new bootstrap.Toast(document.getElementById('toast-alert'));
                toast.show();
            </script>";
        }
        else{
            echo "<script>
                alert('Server Down! Try again later.');
            </script>";
        }
    }
    ?>

    <?php require('inc/footer.php'); ?>

</body>

</html>
