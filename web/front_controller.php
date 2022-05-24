<?php

    require '../vendor/autoload.php';

    require_once '../config.php';
    require_once '../dispatcher.php';
    require_once '../routing.php';
    require_once '../controllers.php';

    session_start();

    $entireAction = $_GET['action'];
    dispatch($routing, $entireAction);
?>