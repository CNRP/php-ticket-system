<?php
//include auth_session.php file on all user panel pages
include "auth/session.php";
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
        <p>Hey, <?php echo $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name']; ?>!</p>
        <br>
        <h2>All Support tickets</h2>
        <?php
            require 'php/db.php';
            require 'php/utils.php';
            // When form submitted, check and create user session.
            if ($_SESSION['user']['user_type'] == 2) {

                $html = "<ul class='tickets orders'>";
                $tickets = dbQuery("SELECT * FROM `tickets` ORDER BY `status`='pending' DESC, `create_datetime` ASC");
                while ($data = mysqli_fetch_assoc($tickets)){
                    $messages = dbQuery("SELECT * FROM `ticket_messages` WHERE ticket_id=".$data['id']." ORDER BY `create_datetime` ASC");
                    $messages_html = "";
                    $total_messages = mysqli_num_rows($messages);
                    while (($messagedata = mysqli_fetch_assoc($messages))){
                        $id = $messagedata['user_id'];
                        $userdata = dbQueryAssoc("SELECT `id`, `first_name`, `last_name`, `email` FROM `users` WHERE id='$id' ORDER BY `create_datetime` ASC");
                        $messages_html .= "
                            <div class='".($id == $_SESSION['user']['id'] ? $class ="poster": $class = "replier")."'>
                                <p class='author'>".$userdata['first_name']." ".$userdata['last_name']."</p>
                                <p class='value'>".$messagedata['message']."</p>
                            </div>
                            ";
                    }
                    $date = date_parse($data['create_datetime']);

                    $html .= '
                        <li>
                            <div class="ticket-status">
                                <p class="value status status-'.$data['status'].'">'.$data['status'].'</p>
                                <div class="right">
                                    <p class="value status-messages">'. $total_messages .' Messages</a>
                                    <p class="value status-messages">'. ($date['day'] < 10 ? '0'.$date['day'] : $date['day'])."/".($date['month'] < 10 ? '0'.$date['month'] : $date['month']) .'</a>
                                </div>
                            </div>
                            <div class="details">
                                <div class="ticket-info">
                                    <p class="label">Ticket ID:</p>
                                    <a class="value" href="support_ticket.php?id='.$data['id'].'">'.$data['id'].'</a>
                                </div>
                                <div class="ticket-info">
                                    <p class="label">User ID:</p>
                                    <a href="account.php?user="'.$data['id'].'" class="value">'.$data['id'].'</a>
                                </div>
                                <div class="ticket-info">
                                    <p class="label">Order Number:</p>
                                    <p class="value">'.$data['order_number'].'</p>
                                </div>
                                <div class="buttons">
                                    <a class="button" href="support_ticket.php?id='.$data['id'].'"><i class="fa-solid fa-circle-arrow-right"></i></a>
                                </div>
                            </div>
                        </li>
                    ';
                }
                echo $html .= "</ul>";
            }
        ?>
        <p><a href="/auth/logout.php">Logout</a></p>
    </div>
</body>
</html>