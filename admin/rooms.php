<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Rooms</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">


    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4 h-font">ROOMS</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-room">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>


                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col" class="t-font ">#</th>
                                        <th scope="col" class="t-font ">Name</th>
                                        <th scope="col" class="t-font ">Area</th>
                                        <th scope="col" class="t-font ">Guests</th>
                                        <th scope="col" class="t-font ">Price</th>
                                        <th scope="col" class="t-font ">Quantity</th>
                                        <th scope="col" class="t-font ">Status</th>
                                        <th scope="col" class="t-font ">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="room-data">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>


    <!-- Add room modal -->
    <div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_room_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Room</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Name Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>

                            <!-- Area Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Area</label>
                                <input type="number" min="1" name="area" class="form-control shadow-none" required>
                            </div>

                            <!-- Price Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Price</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>

                            <!-- Quantity Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Quantity</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                            </div>

                            <!-- Adult Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Adult (Max.)</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                            </div>

                            <!-- Children Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Children (Max.)</label>
                                <input type="number" min="1" name="children" class="form-control shadow-none" required>
                            </div>

                            <!-- Features Section -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold t-font">Features</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('features');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                        <div class='col-md-3 mb-1'>
                                            <label>
                                                <input type='checkbox' name='features[]' value='{$opt['id']}' class='form-check-input shadow-none'>
                                                {$opt['name']}
                                            </label>
                                        </div>
                                    ";
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Facilities Section -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold t-font">Facilities</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('facilities');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                        <div class='col-md-3 mb-1'>
                                            <label>
                                                <input type='checkbox' name='facilities[]' value='{$opt['id']}' class='form-check-input shadow-none'>
                                                {$opt['name']}
                                            </label>
                                        </div>
                                    ";
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold t-font">Description</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-white" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn submit-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit room modal -->
    <div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_room_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Room</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Name Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>

                            <!-- Area Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Area</label>
                                <input type="number" min="1" name="area" class="form-control shadow-none" required>
                            </div>

                            <!-- Price Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Price</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>

                            <!-- Quantity Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Quantity</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                            </div>

                            <!-- Adult Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Adult (Max.)</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                            </div>

                            <!-- Children Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold t-font">Children (Max.)</label>
                                <input type="number" min="1" name="children" class="form-control shadow-none" required>
                            </div>

                            <!-- Features Section -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold t-font">Features</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('features');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                        <div class='col-md-3 mb-1'>
                                            <label>
                                                <input type='checkbox' name='features[]' value='{$opt['id']}' class='form-check-input shadow-none'>
                                                {$opt['name']}
                                            </label>
                                        </div>
                                    ";
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Facilities Section -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold t-font">Facilities</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('facilities');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo "
                                        <div class='col-md-3 mb-1'>
                                            <label>
                                                <input type='checkbox' name='facilities[]' value='{$opt['id']}' class='form-check-input shadow-none'>
                                                {$opt['name']}
                                            </label>
                                        </div>
                                    ";
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold t-font">Description</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>

                            <input type="hidden" name="room_id">

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-white" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn submit-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage room images modal -->

    <div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Room Name</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="image-alert">

                    </div>
                    <div class="border-bottom border-3 pb-3 mb-3">
                        <form id="add_image_form">
                            <label class="form-label fw-bold t-font">Add Image</label>
                            <input type="file" name="image" class="form-control shadow-none mb-3" required>
                            <button class="btn custom-bg text-white shadow-none">ADD</button>
                            <input type="hidden" name="room_id">
                        </form>
                    </div>

                    <div class="table-responsive-lg" style="height: 350px; overflow-y: scroll;">
                        <table class="table table-hover border text-center">
                            <thead>
                                <tr class="bg-dark text-light sticky-top">
                                    <th scope="col" class="t-font " width="60%">Image</th>
                                    <th scope="col" class="t-font ">Thumb</th>
                                    <th scope="col" class="t-font ">Delete</th>
                                </tr>
                            </thead>
                            <tbody id="room-image-data">
                            </tbody>
                        </table>
                    </div>

                </div>
                <div>
                </div>
            </div>
        </div>




        <?php require('inc/script.php'); ?>

        <script src="scripts/rooms.js"></script>

</body>

</html>