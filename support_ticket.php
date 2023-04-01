<?php
//include auth_session.php file on all user panel pages
include "auth/session.php";
include "php/utils.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Client area</title>
    <link rel="stylesheet" href="assets/fa/css/all.min.css">
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <?php include 'php/navigation.php'; ?>
    <div class="content">
        <?php
            require 'php/db.php';

            $ticket = dbQueryAssoc("SELECT * FROM `tickets` WHERE id='".$_GET['id']."' ORDER BY `create_datetime` DESC");
            $user_id = $_SESSION['user']['id'];
            $ticket_user_id = $ticket['user_id'];

            if ($user_id == $ticket_user_id or $_SESSION['user']['user_type'] == 2) {

                if (isset($_REQUEST['message'])) {
                    $create_datetime = date("Y-m-d H:i:s");
                    $message = stripslashes($_REQUEST['message']);
                    $message = mysqli_real_escape_string($con, $message);

                    ($_SESSION['user']['user_type'] == 2 ? $status = "responded" : $status = "pending");
                    $update = dbQuery("UPDATE `tickets` SET `status`='".$status."' WHERE id='".$_GET['id']."'");

                    $id = $_SESSION['user']['id'];
                    $result = dbQuery("INSERT INTO `ticket_messages`( `user_id`, `message`, `create_datetime`, `ticket_id`)
                            VALUES ('$id', '$message', '$create_datetime', '".$ticket['id']. "')");

                    header("Location: ../support_ticket.php?id=".$ticket['id']. "");
                }

                $ticket_messages = dbQuery("SELECT * FROM `ticket_messages` WHERE ticket_id=".$_GET['id']." ORDER BY `create_datetime` DESC");
                $messages_html = "";

                while (($messagedata = mysqli_fetch_assoc($ticket_messages))){
                    $id = $messagedata['user_id'];
                    $userdata = dbQueryAssoc("SELECT `id`, `first_name`, `last_name`, `email` FROM `users` WHERE id='$id'");
                    ($id == $_SESSION['user']['id'] ? $class ="poster": $class = "replier");
                    $date = date_parse($messagedata['create_datetime']);
                    $messages_html .= "
                    <div class='$class'>
                        <p class='author'>".$userdata['first_name']." ".$userdata['last_name']."</p>
                        <p class='value'>".$messagedata['message']."</p>
                        <small><p class='datetime'>".$date['hour'].":".($date['minute'] < 10 ? '0'.$date['minute'] : $date['minute'])." - ".($date['day'] < 10 ? '0'.$date['day'] : $date['day'])."/".($date['month'] < 10 ? '0'.$date['month'] : $date['month'])."</p></small>
                    </div>
                    ";
                }

                $html ='
                <h1>Your support ticket</h1>
                <div class="ticket-status">
                    <p class="value status status-'.$ticket['status'].'">'.($ticket['status'] == "responded" ? "New response" : $ticket['status']).'</p>
                </div>
                <div class="orders">
                    <div class="details">
                        <div class="ticket-id">
                            <p class="label">Ticket ID:</p>
                            <p class="value">'.$ticket['id'].'</p>
                        </div>
                        <div class="order-number">
                            <p class="label">Order Number:</p>
                            <p class="value">'.$ticket['order_number'].'</p>
                        </div>
                        <div class="email">
                            <p class="label">Associated Email:</p>
                            <p class="value">'.$userdata['email'].'</p>
                        </div>
                    </div>
                <div class="messages">
                    '. $messages_html .'
                </div>
                <form class="form" action="" method="post">
                    <textarea id="freeform" name="message" rows="4" cols="50">Enter text here...</textarea>
                    <input type="submit" name="submit" value="Reply" class="login-button">
                </form>
                ';

                echo $html;
            }
        ?>
        </div>
        <p><a href="/auth/logout.php">Logout</a></p>
    </div>
</body>
</html>