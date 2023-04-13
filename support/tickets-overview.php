<?php
//include auth_session.php file on all user panel pages
include "../auth/session.php";
include "../support/tickets-table.php";
require '../auth/db.php';
require '../php/utils.php';

    if ($_SESSION['user']['user_type'] == 2) {
        $tickets = $mysqli->query("SELECT * FROM `tickets` ORDER BY `status`='pending' DESC, `created_at` ASC");
    }
$page_title = "Tickets overview";
include '../php/header.php';
include '../php/navigation.php'; ?>
    <div class="content">
        <h2>Hey, <?php echo $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name']; ?>!</h2>

        <form class="form search" method="post" name="search_tickets">
            <h1>Search for ticket</h1>
            <input type="text" name="value" placeholder="Order Number, Email, Ticket ID, User ID" autofocus="true"/>
            <input type="submit" value="Search" name="submit" class="form-button"/>
        </form>
        <br>
        <?php
            if(isset($_POST['value'])){
                $ticks = $mysqli->query("
                SELECT * FROM tickets
                WHERE display_id = '" . $_POST["value"] . "' OR
                order_number = '" . $_POST["value"] . "' OR
                user_id = '" . $_POST["value"] . "'
                ");
                if($_POST['value'] == ""){
                    $ticks = $mysqli->query("SELECT * FROM `tickets` ORDER BY `status`='pending' DESC, `created_at` ASC");

                }
            }else{
                $ticks = $mysqli->query("SELECT * FROM `tickets` ORDER BY `status`='pending' DESC, `created_at` ASC");
            }
            echo get_table_html($ticks);
        ?>
    </div>
<?php include '../php/footer.php';?>