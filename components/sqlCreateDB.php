<?php
    // didnt know what to name the file so here we are
    // it basically sends the query or whatever

    // i forgot to session_start before like an idiot
    session_start();

    // verifying session bs
    if(!isset($_GET['db']) || !isset($_SESSION['username'])) {
        die("error");
    }

    // idk how many times ive copied and pasted this one bit, should prolly put it in a config page some day
    $host = $_SESSION['hostname'];
    $username = $_SESSION['username'];
    $password = $_SESSION['pass'];
    $database = $_GET['db'];

    // create a new mysqli object
    $mysqli = new mysqli($host, $username, $password);

    // check if connection was successful
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    // new db name
    $databaseName = $database;

    // create the database
    $query = "CREATE DATABASE $databaseName";
    if ($mysqli->query($query)) {
        echo "Database created successfully. You can now <a href='javascript:window.close();'>close this tab</a>.";
    } else {
        echo "Error creating database: " . $mysqli->error;
    }

    // Close the database connection
    $mysqli->close();
?>