<?php
//include auth_session.php file on all user panel pages
include "auth/session.php";
require 'php/db.php';
require 'php/utils.php';

    if ($_SESSION['user']['user_type'] == 2) {
        $tickets = $mysqli->query("SELECT * FROM `tickets` ORDER BY `status`='pending' DESC, `created_at` ASC");
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
        <p>Hey,
            <?php echo $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name']; ?>!</p>
        <br>
        <h2>All Support tickets</h2>
        <?php
        while ($data = mysqli_fetch_assoc($tickets)){
            $total_ticket_messages = $mysqli->query("SELECT COUNT(*) FROM `ticket_messages` WHERE ticket_id=".$data['id']." ORDER BY `created_at` ASC")->fetch_assoc()['COUNT(*)'];
            $date = date_parse($data['created_at']);
        ?>
        <ul class='tickets orders'>
            <li>
                <div class="ticket-status">
                    <p class="value status status-<?php echo $data['status'] ?>"><?php echo $data['status'] ?></p>
                    <div class="right">
                        <p class="value status-messages">
                            <?php echo $total_ticket_messages ?> Messages
                        </p>
                        <p class="value status-messages">
                            <?php echo ($date['day']< 10 ? '0'.$date['day'] : $date['day']). "/".($date['month'] < 10 ? '0'.$date['month'] : $date['month'])  ?>
                        </p>
                    </div>
                </div>
                <div class="details">
                    <div class="ticket-info">
                        <p class="label">Ticket ID:</p>
                        <a class="value" href="support_ticket.php?id=<?php echo $data['id'] ?>"><?php echo $data['id'] ?></a>
                    </div>
                    <div class="ticket-info">
                        <p class="label">User ID:</p>
                        <a href="account.php?user="<?php echo $data['user_id'] ?>" class="value"> <?php echo $data['user_id'] ?></a>
                    </div>
                    <div class="ticket-info">
                        <p class="label">Order Number:</p>
                        <p class="value"><?php echo $data['order_number'] ?></p>
                    </div>
                    <div class="buttons">
                        <a class="button" href="support_ticket.php?id=<?php echo $data['id'] ?>"><i class="fa-solid fa-circle-arrow-right"></i></a>
                    </div>
                </div>
            </li>
        </ul>
        <?php } ?>
        <p><a href="/auth/logout.php">Logout</a></p>
    </div>
</body>
</html>