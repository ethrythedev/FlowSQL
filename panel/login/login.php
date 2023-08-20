<?php
    if(!isset($_POST["login"])) {
        header("Location: ../../");
        exit;
    }

    session_start();

    error_reporting(0);

    echo "<p id='mysqlLoginErrorMsg'>Error while connecting to database. <a href='javascript:window.history.back();'>Go back</a></p>"; // prepare error message

    // Database connection configuration
    $hostname = isset($_POST["loc"]) == true ? $_POST["loc"] : "localhost";
    $username = isset($_POST["username"]) == true ? $_POST["username"] : "";
    $password = isset($_POST["password"]) == true ? $_POST["password"] : "";
    $database = ''; // not logging into db

    // Create a new MySQLi object
    $mysqli = new mysqli($hostname, $username, $password, $database);

    // Check if the connection was successful
    if ($mysqli->connect_errno) {
        // error
        echo "error";
    } else {
        // connection successful
        
        $_SESSION["flowsql__isloggedin"] = true;
        $_SESSION["flowsql__username"] = $username;
        $_SESSION["flowsql__pass"] = $password;
        $_SESSION["flowsql__hostname"] = $hostname;
        
        echo "<p style='color: green;'>Connected to MySQL successfully!</p>";
        echo "<p>You will now be redirected to <a href='../'>the panel</a>.</p>";
        echo "<style>#mysqlLoginErrorMsg { display: none; }</style>";
        echo "<meta http-equiv='refresh' content='0.3;../'>"; // header doesn't work after content has been renderered in PHP.
    }

    $mysqli->close();
?>