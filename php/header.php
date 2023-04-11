<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $page_title ?></title>
    <link rel="stylesheet" href="/assets/fa/css/all.min.css">
    <link rel="stylesheet" href="/css/support/style.css" />

    <?php
        $pages = array('tickets-overview.php', 'account.php', 'ticket.php');
        if (in_array(basename($_SERVER['PHP_SELF']), $pages)):
    ?>
        <link rel="stylesheet" href="/css/support/table-style.css" />
    <?php endif; ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>