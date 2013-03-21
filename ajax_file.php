<?php
    session_start();
    include "config.php";
    if (!isset($_SESSION['authed']) or $_SESSION['authed'] == false)
    {
        echo 'You must have privileges to access this page.';
        die();
    }
    $name = $_GET['name'];
    echo htmlentities(file_get_contents($name));
?>
