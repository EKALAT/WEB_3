<?php
require('inc/essentials.php');
require('inc/db_config.php');

session_start();
if ((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
    require('dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>

    <?php require('inc/links.php') ?>
    <style>
        /* Body background styling */
        body {
            background-image: url('../images/background/99.gif'); /* Your background image */
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Login form container styling */
        div.login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            background-color: rgba(255, 255, 255, 0.85); /* Semi-transparent white background */
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); /* Soft shadow for depth */
            padding: 35px;
            text-align: center;
        }

        /* Form heading */
        h4 {
            background-color: #003366; /* Deep blue for trust and professionalism */
            color: lavender;
            padding: 18px;
            border-radius: 10px 10px 0 0; /* Rounded top corners */
            margin: -35px -35px 25px;
            font-size: 24px;
            font-weight: bold;
        }

        /* Input fields styling */
        .login-form input {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 14px;
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .login-form input:focus {
            border-color: #FFD700; /* Gold highlight on focus */
            outline: none;
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.5); /* Soft gold glow effect */
        }

        /* Login button styling */
        .submit-bg {
            background-color: seagreen; /* Soft golden yellow */
            color: whitesmoke;
            border: none;
            padding: 14px;
            width: 50%;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-bg:hover {
            background-color: red; /* Slightly darker gold on hover */
        }

        /* Responsive styling */
        @media (max-width: 480px) {
            div.login-form {
                width: 85%;
                padding: 20px;
            }

            h4 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="login-form">
        <form method="POST">
            <h4 class="h-font">ADMIN LOGIN PANEL</h4>
            <div class="p-4">
                <div class="mb-3 t-font">
                    <input name="admin_name" required type="text" class="form-control shadow-none text-center" placeholder="Admin Name">
                </div>
                <div class="mb-4 t-font">
                    <input name="admin_pass" required type="password" class="form-control shadow-none text-center" placeholder="Password">
                </div>
                <button name="login" type="submit" class="submit-bg h-font">LOGIN</button>
            </div>
        </form>
    </div>

    <?php

    if (isset($_POST['login'])) {
        $frm_data = filteration($_POST);

        $query = "SELECT * FROM `admin_cred` WHERE `admin_name`=? AND `admin_pass`=?";
        $values = [$frm_data['admin_name'], $frm_data['admin_pass']];

        $res = select($query, $values, "ss");
        if ($res->num_rows == 1) {
            $row = mysqli_fetch_assoc($res);
            $_SESSION['adminLogin'] = true;
            $_SESSION['adminId'] = $row['sr_no'];
            redirect('dashboard.php');
        } else {
            alert('error', 'Login failed - Invalid Credentials!');
        }
    }

    ?>

    <?php require('inc/script.php') ?>

</body>

</html>
