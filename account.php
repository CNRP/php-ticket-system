<?php
include "auth/session.php";
include "support/tickets-table.php";
require 'auth/db.php';
require 'php/utils.php';

$id = $_SESSION['user']['id'];
$tickets = $mysqli->query("SELECT * FROM `tickets` WHERE user_id=$id ORDER BY `status`='pending' DESC, `created_at` ASC");

$page_title = "Account View";
include 'php/header.php';
include 'php/navigation.php';
?>
    <div class="content">
        <h2>Hey, <?php echo $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name']; ?>!</h2>
        <h4>Email: <?php echo $_SESSION['user']['email'] ?></h4>
        <h4>Account Created: <?php echo $_SESSION['user']['created_at']?></h4>
        <h2>Your support tickets</h2>
        <?php echo get_table_html($tickets); ?>

        <a class="button" href="/auth/logout.php">Logout</a>
        <?php if($_SESSION['user']['user_type'] == 2){ ?>
            <br>
            <a href="/support/tickets-overview.php" class="button">All Tickets</a>
        <?php } ?>
    </div>
<?php include 'php/footer.php';?>