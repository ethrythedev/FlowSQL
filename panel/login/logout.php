<?php
    session_start();
    unset($_SESSION['flowsql__hostname']);
    unset($_SESSION['flowsql__username']);
    unset($_SESSION['flowsql__pass']);
    session_abort();
    
    header("Location: ../../");
    exit;
?>