<?php
session_start();
session_destroy();
$response = [];

if (isset($_SESSION['user'])) {
    unset($_SESSION['user']); // Clear session data
    $response['status'] = 'success';
    $response['msg'] = 'Logout successful.';
} else {
    $response['status'] = 'error';
    $response['msg'] = 'No user session found.';
}

header('Content-Type: application/json');
echo json_encode($response);
