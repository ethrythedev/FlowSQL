<?php
    // didnt know what to name the file so here we are
    // it basically sends the query or whatever

    // i forgot to session_start before like an idiot
    session_start();

    // verifying session bs
    if(!isset($_GET['db']) || !isset($_SESSION['flowsql__username'])) {
        die("error");
    }

    // idk how many times ive copied and pasted this one bit, should prolly put it in a config page some day
    $host = $_SESSION['flowsql__hostname'];
    $username = $_SESSION['flowsql__username'];
    $password = $_SESSION['flowsql__pass'];
    $database = base64_decode($_GET['db']);

    // create mysqli object
    $mysqli = new mysqli($host, $username, $password, $database);

    // if fail do some stuff
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    // get query from GET idfk
    $query = $_GET['sqlQuery'];

    // Execute the query
    $result = $mysqli->query($query);

    // Check if the query was successful
    if (!$result) {
        echo "Error executing the query: " . $mysqli->error;
        exit();
    }

    echo "<p>Success!</p>";
    
    // Process the query result
    while ($row = $result->fetch_assoc()) {
        $finishedStuff = array();
        foreach ($row as $key => $value) {
            $finishedStuff += array($key => $value);
        }

        echo "<code style='color: darkmagenta;'>";
        print_r($finishedStuff);
        echo "</code>";
        echo "<br><br>";
    }

    // Close the database connection
    $mysqli->close();
?>