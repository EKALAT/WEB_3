<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();


if (isset($_POST['get_users'])) {
    $res = selectAll('users');
    $i = 1;



    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {

        $del_btn = "<button onclick='remove_user($row[id])' class='btn btn-danger shadow-none '>
                <i class = 'bi bi-trash'></i>
                </button>";
        
        $data .= "
            <tr class='align-middle'>
                <td>$i</td>
                <td>$row[name]</td>
                <td>$row[email]</td>
                <td>$row[phone]</td>
                <td>$row[location]</td>
                <td>$row[dob]</td>
                <td>$del_btn</td>
            </tr>
                

        ";
        $i++;
    }
    echo $data;
}

if (isset($_POST['remove_user'])) {
    $frm_data = filteration($_POST); // Sanitizing input data
    
    // Execute delete query
    $res = delete("DELETE FROM `users` WHERE `id` = ?", [$frm_data['user_id']], 'i');
    // Send appropriate response
    if ($res) {
        echo 1; // Success
    } else {
        echo 0; // Failure
    }
}

if (isset($_POST['search_user'])) {
    $frm_data = filteration($_POST);
    
    $query = "SELECT * FROM `users` WHERE `name` LIKE ?";
    $res = select($query,["%$frm_data[name]%"],'s');
    $i = 1;



    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {

        $del_btn = "<button onclick='remove_user($row[id])' class='btn btn-danger shadow-none '>
                <i class = 'bi bi-trash'></i>
                </button>";
        
        $data .= "
            <tr class='align-middle'>
                <td>$i</td>
                <td>$row[name]</td>
                <td>$row[email]</td>
                <td>$row[phone]</td>
                <td>$row[location]</td>
                <td>$row[dob]</td>
                <td>$del_btn</td>
            </tr>
                

        ";
        $i++;
    }
    echo $data;
}