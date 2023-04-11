<?php

    $mysqli = new mysqli("localhost", "root", "", "ticketsystem");

    // Check connection
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        die("Failed to connect to MySQL: " . $mysqli->connect_error);
    }

?>