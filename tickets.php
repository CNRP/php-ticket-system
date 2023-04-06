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
    <link rel="stylesheet" href="table-style.css" />
</head>

<body>
    <?php include 'php/navigation.php'; ?>
    <div class="content">
        <h2>Hey, <?php echo $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name']; ?>!</h2>

        <form class="form search" method="post" name="login">
            <h1 class="login-title">Search for ticket</h1>
            <input type="text" class="login-input" name="value" placeholder="Order Number, Email, Ticket ID, User ID" autofocus="true"/>
            <input type="submit" value="Search" name="submit" class="form-button"/>
        </form>
        <br>

        <h2>All Support tickets</h2>
        <table>
            <thead>
                <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Category</th>
                <th>Subject</th>
                <th>Created By</th>
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
                    <td>Category</td>
                    <td>Subject</td>
                    <td><?php echo $data['user_id'] ?></td>
                    <td class="<?php echo $data['status'] ?>"><?php echo $data['status'] ?></td>
                    <td class="buttons"><a class="button">Edit</a><a class="button">Delete</a></td>
                </tr>
        <?php } ?>
            </tbody>
        </table>
        <a href="/auth/logout.php" class="button">Logout</a>
    </div>
</body>
</html>