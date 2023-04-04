<?php
include "auth/session.php";
require 'php/db.php';
require 'php/utils.php';

    $id = $_SESSION['user']['id'];
    if (isset($id)) {
        $tickets = $mysqli->query("SELECT * FROM `tickets` ORDER BY `status`='pending' DESC, `created_at` ASC");
        $to = "connorefc97@gmail.com";
        $subject = "Order Confirmation";
        $message = "Thank you for your order! Your order number is XXX.";
        $headers = "From: yourname@example.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        mail($to, $subject, $message, $headers);
    }

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
        <p>Account Created: <?php echo $_SESSION['user']['created_at']?></p>
        <br>
        <?php if($_SESSION['user']['user_type'] == 2){ ?>
            <a href="tickets.php" class="button">All Tickets</a>
        <?php } ?>

        <h2>Your support tickets</h2>
        <?php
        while ($ticket = mysqli_fetch_assoc($tickets)){
            $total_ticket_messages = $mysqli->query("SELECT COUNT(*) FROM `ticket_messages` WHERE ticket_id=".$ticket['id']." ORDER BY `created_at` ASC")->fetch_assoc()['COUNT(*)'];
            $ticket_date = date_parse($ticket['created_at']);
        ?>
        <ul class='tickets orders'>
            <li>
                <div class="ticket-status">
                    <p class="value status status-<?php echo $ticket['status'] ?>"><?php echo $ticket['status'] ?></p>
                    <div class="right">
                        <p class="value status-messages">
                            <?php echo $total_ticket_messages ?> Messages
                        </p>
                        <p class="value status-messages">
                            <?php echo ($ticket_date['day']< 10 ? '0'.$ticket_date['day'] : $ticket_date['day']). "/".($ticket_date[ 'month'] < 10 ? '0'.$ticket_date['month'] : $ticket_date['month']) ?>
                        </p>
                    </div>
                </div>
                <div class="details">
                    <div class="ticket-info">
                        <p class="label">Ticket ID:</p>
                        <a class="value" href="support_ticket.php?id=<?php echo $ticket['id'] ?>"><?php echo $ticket['id'] ?></a>
                    </div>
                    <div class="ticket-info">
                        <p class="label">User ID:</p>
                        <a href="account.php?user="<?php echo $ticket['user_id'] ?>" class="value"> <?php echo $ticket['user_id'] ?></a>
                    </div>
                    <div class="ticket-info">
                        <p class="label">Order Number:</p>
                        <p class="value"><?php echo $ticket['order_number'] ?></p>
                    </div>
                    <div class="buttons">
                        <a class="button" href="support_ticket.php?id=<?php echo $ticket['id'] ?>"><i class="fa-solid fa-circle-arrow-right"></i></a>
                    </div>
                </div>
            </li>
        </ul>
        <?php } ?>
        <p><a href="/auth/logout.php">Logout</a></p>
    </div>
</body>
</html>