<?php
include "auth/session.php";
require 'php/db.php';
require 'php/utils.php';

    $id = $_SESSION['user']['id'];
    $tickets = $mysqli->query("SELECT * FROM `tickets` WHERE user_id=$id ORDER BY `status`='pending' DESC, `created_at` ASC");
    console_log($tickets->num_rows);
    // if (isset($id)) {
    //     $tickets = $mysqli->query("SELECT * FROM `tickets` ORDER BY `status`='pending' DESC, `created_at` ASC");
    //     $to = "connorefc97@gmail.com";
    //     $subject = "Order Confirmation";
    //     $message = "Thank you for your order! Your order number is XXX.";
    //     $headers = "From: yourname@example.com\r\n";
    //     $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    //     mail($to, $subject, $message, $headers);
    // }

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Client area</title>
    <link rel="stylesheet" href="assets/fa/css/all.min.css">
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="table-style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
    <?php include 'php/navigation.php'; ?>
    <div class="content">
        <h2>Hey, <?php echo $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name']; ?>!</h2>
        <h4>Email: <?php echo $_SESSION['user']['email'] ?></h4>
        <h4>Account Created: <?php echo $_SESSION['user']['created_at']?></h4>
        <br>
        <?php if($_SESSION['user']['user_type'] == 2){ ?>
            <a href="tickets.php" class="button">All Tickets</a>
        <?php } ?>

        <h2>Your support tickets</h2>
        <table>
            <thead>
                <tr>
                <th>OrderID</th>
                <th>Date</th>
                <th>Category</th>
                <th class="subject">Subject</th>
                <th>Messages</th>
                <th>Status</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
        <?php
        while ($data = mysqli_fetch_assoc($tickets)){
            $total_ticket_messages = $mysqli->query("SELECT COUNT(*) FROM `ticket_messages` WHERE ticket_id=".$data['id']." ORDER BY `created_at` ASC")->fetch_assoc()['COUNT(*)'];
            $date = date_parse($data['created_at']);
        ?>
                <tr>
                    <td><?php echo $data['order_number'] ?></td>
                    <td><?php echo ($date['day']< 10 ? '0'.$date['day'] : $date['day']). "/".($date['month'] < 10 ? '0'.$date['month'] : $date['month'])  ?></td>
                    <td><?php echo $data['category'] ?></td>
                    <td><?php echo $data['subject'] ?></td>
                    <td><?php echo $total_ticket_messages ?></td>
                    <td class="<?php echo $data['status'] ?> status"><p style="--colour: var(--colour-<?php echo $data['status'] ?>)"><?php echo $data['status'] ?></p></td>
                    <td> 
                        <div class="buttons">
                            <a href="support_ticket.php?id=<?php echo $data['id'] ?>" class="button">
                                <i class="fa-solid fa-up-right-from-square"></i>
                            </a>
                            <a class="button">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </td>
                </tr>
        <?php } ?>
            </tbody>
        </table>
        <a class="button" href="/auth/logout.php">Logout</a>
    </div>
</body>
</html>