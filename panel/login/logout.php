<?php
    session_start();

    // Reset the specific session variables
    $_SESSION['flowsql__isloggedin'] = false;
    unset($_SESSION['flowsql__hostname']);
    unset($_SESSION['flowsql__username']);
    unset($_SESSION['flowsql__pass']);
    
    header("Location: ../../");
    exit;
?>
