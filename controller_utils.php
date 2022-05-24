<?php
require_once 'business.php';

function minimum($a, $b) {
    if ($a < $b) {
        return $a;
    } else {
        return $b;
    }
}

function maximum($a, $b) {
    if ($a > $b) {
        return $a;
    } else {
        return $b;
    }
}

function get_pages($page, $useSession) {
    $globalConfig = include('config.php');
    $array = [];

    for ($i = maximum(1, $page-3); $i <= minimum(get_total_image_pages($useSession), $page+3); $i++) {
        array_push($array, $i);
    }

    if ($array[0] != 1 && count($array) > 0) {
        array_unshift($array, $globalConfig['pageSkip']);
        array_unshift($array, 1);
    }

    if ($array[count($array)] == get_total_image_pages($useSession) && count($array) > 0) {
        array_push($array, $globalConfig['pageSkip']);
        array_push($array, get_total_image_pages());
    }

    return $array;
}

function login_data(&$model) {
    $model['loggedin'] = $_SESSION['loggedin'];
    if ($_SESSION['loggedin']) {
        $model['username'] = $_SESSION['username'];
    }
}