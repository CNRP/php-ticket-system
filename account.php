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
        <p>Email: <?php echo $_SESSION['user']['email'] ?></p>
        <p>Account Created: <?php echo $_SESSION['user']['create_datetime']?></p>
        <br>
        <?php if($_SESSION['user']['user_type']){ ?>
            <a href="tickets.php" class="button">All Tickets</a>
        <?php } ?>
        <!-- <p>You are now user dashboard page.</p> -->

        <h2>Your support tickets</h2>
        <?php
            require 'php/db.php';
            require 'php/utils.php';
            // When form submitted, check and create user session.
            $id = $_SESSION['user']['id'];
            if (isset($id)) {

                $html = "<ul class='orders tickets'>";
                $tickets = dbQuery("SELECT * FROM `tickets` WHERE user_id='$id' ORDER BY `create_datetime` DESC");
                while ($data = mysqli_fetch_assoc($tickets)){
                    $html .= '
                        <li>
                            <div class="ticket-status">
                                <p class="value status status-'.$data['status'].'">'.($data['status'] == "responded" ? "New response" : $data['status']).'</p>
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
                                    <a class="value">'.$data['id'].'</a>
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