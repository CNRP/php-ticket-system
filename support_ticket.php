<?php
//include auth_session.php file on all user panel pages
include "auth/session.php";
include "php/utils.php";
require "php/db.php";

$ticket = $mysqli->query("SELECT * FROM `tickets` WHERE id='" . $_GET["id"] . "' ORDER BY `created_at` DESC")->fetch_assoc();
$user_id = $_SESSION["user"]["id"];
$ticket_user_id = $ticket["user_id"];
$url = "?id=" . $ticket['id'];

if(isset($_GET["status_set"]) && $_SESSION["user"]["user_type"] == 2){
    $stmt = $mysqli->prepare("UPDATE `tickets` SET `status`=? WHERE `id`=?");
    $stmt->bind_param("si", $_GET["status_set"], $_GET["id"]);
    // Execute query
    if ($stmt->execute()) {
        header("Location: ../support_ticket.php".$url);
        exit;
    } else {
        // Registration failed
        $error = 'Failed to change status of ticket. Please try again later';
    }
}

if ($user_id == $_SESSION["user"]["id"] or $_SESSION["user"]["user_type"] == 2) {
    if (isset($_REQUEST["message"])) {

        $_SESSION["user"]["user_type"] == 2
            ? ($ticket_status = "responded")
            : ($ticket_status = "pending");
        $update_status = $mysqli->query("UPDATE `tickets` SET `status`='" . $ticket_status ."' WHERE id='" .$_GET["id"] ."'");

        $stmt = $mysqli->prepare("INSERT INTO ticket_messages (user_id, message, ticket_id) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $_SESSION['user']['id'], $_POST['message'], $ticket["id"] );
        $stmt->execute();

        header("Location: ../support_ticket.php?id=" . $ticket["id"]);
    }

    $ticket_messages = $mysqli->query("SELECT * FROM `ticket_messages` WHERE ticket_id=" . $_GET["id"] . " ORDER BY `created_at` DESC");
    $ticket_message_total = mysqli_num_rows($ticket_messages);
    $ticket_message_count = 1;

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
            <a href="<?php echo $url?>&status_set=pending" class="status-pending">Pending</a>
            <a href="<?php echo $url?>&status_set=responded" class="status-responded">Responded</a>
            <a href="<?php echo $url?>&status_set=resolved" class="status-resolved">Resolved</a>
            <a href="<?php echo $url?>&status_set=closed" class="status-closed">Closed</a>
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
                    <p class="value"><?php echo $ticket['id'] ?></p>
                </div>
                <div class="ticket-info">
                    <p class="label">Order Number:</p>
                    <p class="value"><?php echo $ticket['order_number']?></p>
                </div>
                <div class="ticket-info">
                    <p class="label">User ID:</p>
                    <p class="value"><?php echo $ticket['user_id']?></p>
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

                while ($message_data = mysqli_fetch_assoc($ticket_messages)) {
                    $id = $message_data["user_id"];
                    $author_user_data =  $mysqli->query("SELECT `id`, `first_name`, `last_name`, `email` FROM `users` WHERE id='$id'")->fetch_assoc();
                    $message_date = date_parse($message_data["created_at"]);

                    if ($ticket_message_total == $ticket_message_count) {
                    ?>
                        <div class='<?php echo ($_SESSION["user"]["user_type"] == 2 ? ($class = "poster") : ($class = "replier")) ?>'>
                            <p class='author'>Automated Response <i class='fa-solid fa-robot'></i></p>
                            <p class='value'>Hey. Thanks for contacting us, we will try our hardest to get back to you in a timely manner. Usually within a few hours!</p>
                            <small>
                                <p class='datetime'><?php echo $message_date["hour"] . ":" . ($message_date["minute"] < 10 ? "0" . $message_date["minute"] : $message_date["minute"]) . " - " . ($message_date["day"] < 10 ? "0" . $message_date["day"] : $message_date["day"]) . "/" . ($message_date["month"] < 10 ? "0" . $message_date["month"] : $message_date["month"]) ?> </p>
                            </small>
                        </div>

                    <?php
                    }
                    $ticket_message_count++;
                    ?>

                <div class='<?php echo ($id == $_SESSION["user"]["id"]? ($class = "poster"): ($class = "replier")) ?>'>
                    <p class='author'><?php echo $author_user_data["first_name"] ." " .substr($author_user_data["last_name"], 0, 1)?> </p>
                    <p class='value'><?php echo $message_data["message"] ?></p>
                    <small>
                        <p class='datetime'><?php echo $message_date["hour"] . ":" . ($message_date["minute"] < 10 ? "0" . $message_date["minute"] : $message_date["minute"]) . " - " . ($message_date["day"] < 10 ? "0" . $message_date["day"] : $message_date["day"]) ."/" . ($message_date["month"] < 10 ? "0" . $message_date["month"] : $message_date["month"]) ?></p>
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