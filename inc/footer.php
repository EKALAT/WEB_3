<div class="container-fluid bg-white mt-5">
    <div class="row">
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3 mb-2">PMS HOTEL</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. At dolorum, nostrum placeat consequuntur beatae accusamus. Eaque, molestias! Laudantium voluptatibus pariatur fugiat assumenda facilis quod sapiente, aperiam deserunt reiciendis tenetur. Exercitationem.</p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Links</h5>
            <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a><br>
            <a href="rooms.php" class="d-inline-block mb-2 text-dark text-decoration-none">Rooms</a><br>
            <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none">Facilities</a><br>
            <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none">Contact us</a><br>
            <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none">About</a>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Follow us</h5>
            <?php
            if ($contact_r['tw'] != '') {
                echo <<<data
                    <a href="{$contact_r['tw']}" class="d-inline-block text-dark text-decoration-none mb-2">
                        <i class="bi bi-twitter me-1"></i> Twitter
                    </a><br>
                data;
            }
            ?>
            <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-facebook me-1"></i> Facebook
            </a><br>
            <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark text-decoration-none">
                <i class="bi bi-instagram me-1"></i> Instagram
            </a><br>
        </div>
    </div>
</div>

<h6 class="text-center bg-dark text-white p-3 m-0">Designed and Developed by PHOMMASENG EKALAT CNTT K64</h6>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

<script>
    function alert(type, msg, position = 'body') {
        let bs_class = (type === 'success') ? 'alert-success' : 'alert-danger';
        
        // Remove any existing alerts
        const existingAlert = document.querySelector('.alert');
        if (existingAlert) existingAlert.remove();

        // Create and insert alert
        let element = document.createElement('div');
        element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
                <strong class="me-3 t-font">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        if (position === 'body') {
            document.body.append(element);
            element.classList.add('custom-alert');
        } else {
            document.getElementById(position).appendChild(element);
        }

        setTimeout(() => {
            if (element) element.remove();
        }, 3000);
    }

    function setActive() {
        let navbar = document.getElementById('nav-bar');
        let a_tags = navbar ? navbar.getElementsByTagName('a') : [];

        for (let i = 0; i < a_tags.length; i++) {
            let file = a_tags[i].href.split('/').pop();
            let file_name = file.split('.')[0];

            if (document.location.href.indexOf(file_name) >= 0) {
                a_tags[i].classList.add('active');
            }
        }
    }

    // let register_form = document.getElementById('register_form');
    // if (register_form) {
    //     register_form.addEventListener('submit', (e) => {
    //         e.preventDefault();

    //         let data = new FormData(register_form);
    //         data.append('register', '');

    //         var myModal = document.getElementById('registerModal');
    //         var modal = bootstrap.Modal.getInstance(myModal);
    //         modal.hide();

    //         let xhr = new XMLHttpRequest();
    //         xhr.open("POST", "ajax/login_register.php", true);

    //         xhr.onload = function() {
    //             let response = this.responseText;
    //             if (response === 'pass_mismatch') {
    //                 alert('error', "Password Mismatch!");
    //             } else if (response === 'email_already') {
    //                 alert('error', "Email is already registered!");
    //             } else if (response === 'phone_already') {
    //                 alert('error', "Phone number is already registered!");
    //             } else if (response === 'upd_failed') {
    //                 alert('error', "Image upload failed");
    //             } else if (response === 'mail_failed') {
    //                 alert('error', "Cannot send confirmation email! Server down!");
    //             } else if (response === 'ins_failed') {
    //                 alert('error', "Registration failed! Server down!");
    //             } else {
    //                 alert('success', "Registration successful. Confirmation link sent to your email");
    //                 register_form.reset();
    //             }
    //         }

    //         xhr.send(data);
    //     });
    // }


    // function checkLoginToBook(status,room_id){
    //     if(status){
    //         window.location.href='confirm_booking.php?id='+room_id;
    //     }
    // }

    setActive();



</script>
