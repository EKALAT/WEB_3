<?php 
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("Asia/Ho_Chi_Minh");

if(isset($_POST['check_availability']))
{
    session_start();

    $frm_data = filteration($_POST);
    $status = "";
    $result = "";

    // Check-in and check-out validations
    $today_date = new DateTime(date("Y-m-d"));
    $checkin_date = new DateTime($frm_data['check_in']);
    $checkout_date = new DateTime($frm_data['check_out']);

    if($checkin_date == $checkout_date){
        $status = 'check_in_out_equal';
        $result = json_encode(["status"=>$status]);
    }
    else if($checkout_date < $checkin_date){
        $status = 'check_out_earlier';
        $result = json_encode(["status"=>$status]);
    }
    else if($checkin_date < $today_date){
        $status = 'check_in_earlier';
        $result = json_encode(["status"=>$status]);
    }

    // Check if room is available
    if($status != ''){
        echo $result;
    }
    else{
        if (isset($_SESSION['room'])) {
            $room = $_SESSION['room'];

            // Run query to check room availability (assuming you're using this logic for actual availability check)
            $count_days = date_diff($checkin_date, $checkout_date)->days;
            $payment = $room['price'] * $count_days;

            $_SESSION['room']['payment'] = $payment;
            $_SESSION['room']['available'] = true;

            $result = json_encode(["status"=>'available', "days"=>$count_days, "payment"=>$payment]);
        } else {
            $result = json_encode(["status" => 'unavailable']);
        }

        echo $result;
    }
}
?>
