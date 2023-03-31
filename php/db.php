<?php
    // Enter your host name, database username, password, and database name.
    // If you have not set database password on localhost then set empty.
    $con = mysqli_connect("localhost", "root", "", "ticketsystem");
    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    function dbQuery($query){
        $result = mysqli_query($GLOBALS['con'], $query) or die();
        return $result;
    }

    function dbQueryAssoc($query){
        $result = mysqli_query($GLOBALS['con'], $query) or die();
        return mysqli_fetch_assoc($result);
    }
?>