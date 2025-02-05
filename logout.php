<?php
    session_start();
    foreach($_SESSION as $key => $values){
        unset($_SESSION[$key]);
    }
    header('Location: login.php');
?>