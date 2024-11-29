<?php

// Frontend URLs
define('SITE_URL', 'http://127.0.0.1/EKALAT_PMS/');
define('ABOUT_IMG_PATH', SITE_URL . 'images/about/');
define('PMSHOTEL_IMG_PATH', SITE_URL . 'images/pms/');
define('FACILITIES_IMG_PATH', SITE_URL . 'images/facilities/');
define('ROOMS_IMG_PATH', SITE_URL . 'images/rooms/');

// Backend file paths
define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/EKALAT_PMS/images/');
define('ABOUT_FOLDER', 'about/');
define('PMSHOTEL_FOLDER', 'pms/');
define('FACILITIES_FOLDER', 'facilities/');
define('ROOMS_FOLDER', 'rooms/');
define('USERS_FOLDER', 'users/');






function adminLogin()
{
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
        exit;
    }
}

function redirect($url)
{
    echo "<script>window.location.href='$url';</script>";
    exit;
}

function alert($type, $msg)
{
    $bs_class = ($type == "success") ? "alert-success" : "alert-danger";
    echo <<<alert
        <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
            <strong class="me-3 t-font">$msg</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    alert;
}

function uploadImage($image, $folder)
{
    $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img'; // Invalid image format
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_' . random_int(11111, 99999) . ".$ext";
        $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;

        if (move_uploaded_file($image['tmp_name'], $img_path)) {
            return $rname;
        } else {
            return 'upd_failed';
        }
    }
}

function uploadSVGImage($image, $folder)
{
    $valid_mime = ['image/svg+xml'];
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img'; // Invalid image format
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_' . random_int(11111, 99999) . ".$ext";
        $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;

        if (move_uploaded_file($image['tmp_name'], $img_path)) {
            return $rname;
        } else {
            return 'upd_failed';
        }
    }
}

function deleteImage($image, $folder)
{
    return unlink(UPLOAD_IMAGE_PATH . $folder . $image) ? 'del_success' : 'del_failed';
}

function uploadUserImage($image)
{
    $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img'; // Invalid image format
    } else {
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $rname = 'IMG_' . random_int(11111, 99999) . ".jpeg"; // Consistent output format

        $img_path = UPLOAD_IMAGE_PATH . USERS_FOLDER . $rname;

        // Convert to JPEG format if necessary
        switch ($ext) {
            case 'png':
                $img = imagecreatefrompng($image['tmp_name']);
                break;
            case 'webp':
                $img = imagecreatefromwebp($image['tmp_name']);
                break;
            default:
                $img = imagecreatefromjpeg($image['tmp_name']);
        }

        if (imagejpeg($img, $img_path, 75)) {
            imagedestroy($img); // Free up memory
            return $rname;
        } else {
            return 'upd_failed';
        }
    }
}
