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
        <!-- <p>You are now user dashboard page.</p> -->

        <h2>All Support tickets</h2>
        <?php
            require 'php/db.php';
            require 'php/utils.php';
            // When form submitted, check and create user session.
            if ($_SESSION['user']['user_type'] == 2) {

                $html = "<ul class='tickets orders'>";
                $tickets = dbQuery("SELECT * FROM `tickets` ORDER BY `create_datetime` DESC");
                while ($data = mysqli_fetch_assoc($tickets)){
                    $messages = dbQuery("SELECT * FROM `ticket_messages` WHERE ticket_id=".$data['id']." ORDER BY `create_datetime` ASC");
                    $messages_html = "";
                    while (($messagedata = mysqli_fetch_assoc($messages))){
                        $id = $messagedata['user_id'];
                        $userdata = dbQueryAssoc("SELECT `id`, `first_name`, `last_name`, `email` FROM `users` WHERE id='$id' ORDER BY `create_datetime` ASC");

                        $class = "replier";
                        if($id == $_SESSION['user']['id']){
                            $class = "poster";
                        }
                        $messages_html .= "
                            <div class='$class'>
                                <p class='author'>".$userdata['first_name']." ".$userdata['last_name']."</p>
                                <p class='value'>".$messagedata['message']."</p>
                            </div>
                            ";
                    }

                    $html .= '
                        <li>
                            <div class="ticket-status">
                                <p class="value status status-'.$data['status'].'">'.$data['status'].'</p>
                            </div>
                            <div class="details">
                                <div class="ticket-id">
                                    <p class="label">Ticket ID:</p>
                                    <a class="value" href="support_ticket.php?id='.$data['id'].'">'.$data['id'].'</a>
                                </div>
                                <div class="order-number">
                                    <p class="label">Order Number:</p>
                                    <p class="value">'.$data['order_number'].'</p>
                                </div>
                                <div class="email">
                                    <p class="label">Associated User ID:</p>
                                    <a href="account.php?user="'.$data['id'].'" class="value">'.$data['id'].'</a>
                                </div>
                                <div class="buttons">
                                    <a class="button" href="support_ticket.php?id='.$data['id'].'"> Expand Ticket </a>
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