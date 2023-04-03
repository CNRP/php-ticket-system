<?php
//include auth_session.php file on all user panel pages
include "auth/session.php";
include "php/utils.php";
require "php/db.php";

$ticket = dbQueryAssoc("SELECT * FROM `tickets` WHERE id='" . $_GET["id"] . "' ORDER BY `create_datetime` DESC");
$user_id = $_SESSION["user"]["id"];
$ticket_user_id = $ticket["user_id"];

if ($ticket["user_id"] == $_SESSION["user"]["id"] or $_SESSION["user"]["user_type"] == 2) {
    if (isset($_REQUEST["message"])) {
        $create_datetime = date("Y-m-d H:i:s");
        $message = stripslashes($_REQUEST["message"]);
        $message = mysqli_real_escape_string($con, $message);

        $_SESSION["user"]["user_type"] == 2
            ? ($status = "responded")
            : ($status = "pending");
        $update = dbQuery("UPDATE `tickets` SET `status`='" .$status ."' WHERE id='" .$_GET["id"] ."'");

        $result = dbQuery("INSERT INTO `ticket_messages`( `user_id`, `message`, `create_datetime`, `ticket_id`) VALUES ('".$_SESSION["user"]["id"]."', '$message', '$create_datetime', '" . $ticket["id"] ."')");
        header("Location: ../support_ticket.php?id=" . $ticket["id"] . "");
    }

    $ticket_messages = dbQuery("SELECT * FROM `ticket_messages` WHERE ticket_id=" . $_GET["id"] . " ORDER BY `create_datetime` DESC");
    $messages_html = "";

    $total_messages = mysqli_num_rows($ticket_messages);
    $message_count = 1;
    $alert_text = ($ticket["status"] == "closed" ? "This ticket has been marked as closed, if your issue wasnt resolved feel free to reply here or open another ticket." : "");
    $alert_text = ($ticket["status"] == "resolved" ? "This ticket has been marked as resolved, if you need help with another issue, feel free to open another ticket." : $alert_text);
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
    <?php include "php/navigation.php"; ?>

    <div class="content">
        <div class="update-status">
            <h4>Mark as:</h4>
            <a href="?status_set=pending" class="status-pending">Pending</a>
            <a href="?status_set=pending" class="status-responded">Responded</a>
            <a href="?status_set=pending" class="status-resolved">Resolved</a>
            <a href="?status_set=pending" class="status-closed">Closed</a>
        </div>
        <h1>Your support ticket</h1>
        <div class="ticket-status">
            <p class="value status status-<?php echo $ticket['status']?>">
                <?php echo ($ticket['status'] == "responded" ? "New response" : $ticket['status']) ?>
            </p>
        </div>
        <div class="orders">
            <div class="details">
                <div class="ticket-info">
                    <p class="label">Ticket ID:</p>
                    <p class="value">
                        <?php echo $ticket['id'] ?> </p>
                </div>
                <div class="ticket-info">
                    <p class="label">Order Number:</p>
                    <p class="value">
                        <?php echo $ticket['order_number']?> </p>
                </div>
                <div class="ticket-info">
                    <p class="label">User ID:</p>
                    <p class="value">
                        <?php echo $ticket['user_id']?> </p>
                </div>
            </div>

            <div class="messages">
                <?php if(isset($alert_text) && $alert_text !== '') { ?>
                    <div class='status-reply status-<?php echo $ticket["status"] ?>'>
                        <p class='alert'>
                            <i class='fa-solid fa-robot'></i><?php echo $alert_text ?>
                        </p>
                    </div>
                <?php }

                while ($messagedata = mysqli_fetch_assoc($ticket_messages)) {
                    $id = $messagedata["user_id"];
                    $userdata = dbQueryAssoc("SELECT `id`, `first_name`, `last_name`, `email` FROM `users` WHERE id='$id'");
                    $date = date_parse($messagedata["create_datetime"]);

                    if ($message_count == $total_messages) {
                    $date = date_parse($ticket["create_datetime"]);
                    ?>
                        <div class='<?php echo ($_SESSION["user"]["user_type"] == 2 ? ($class = "poster") : ($class = "replier")) ?>'>
                            <p class='author'>Automated Response <i class='fa-solid fa-robot'></i></p>
                            <p class='value'>Hey. Thanks for contacting us, we will try our hardest to get back to you in a timely manner. Usually within a few hours!</p>
                            <small>
                                <p class='datetime'><?php echo $date["hour"] . ":" . ($date["minute"] < 10 ? "0" . $date["minute"] : $date["minute"]) . " - " . ($date["day"] < 10 ? "0" . $date["day"] : $date["day"]) . "/" . ($date["month"] < 10 ? "0" . $date["month"] : $date["month"]) ?> </p>
                            </small>
                        </div>

                    <?php
                    }
                    $message_count++;
                    ?>

                <div class='<?php echo ($id == $_SESSION["user"]["id"]? ($class = "poster"): ($class = "replier")) ?>'>
                    <p class='author'><?php echo $userdata["first_name"] ." " .substr($userdata["last_name"], 0, 1)?> </p>
                    <p class='value'><?php echo $messagedata["message"] ?></p>
                    <small>
                        <p class='datetime'><?php echo $date["hour"] . ":" . ($date["minute"] < 10 ? "0" . $date["minute"] : $date["minute"]) . " - " . ($date["day"] < 10 ? "0" . $date["day"] : $date["day"]) ."/" . ($date["month"] < 10 ? "0" . $date["month"] : $date["month"]) ?></p>
                    </small>
                </div>
                <?php } ?>
            </div>
            <form class="form" action="" method="post">
                <textarea id="freeform" name="message" rows="4" cols="50" placeholder="Please explain your problem here in as much detail as possible"></textarea>
                <input type="submit" name="submit" value="Reply" class="login-button">
            </form>
        </div>
        <p><a href="/auth/logout.php">Logout</a></p>
    </div>
</body>

</html>