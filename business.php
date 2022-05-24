<?php

use MongoDB\BSON\ObjectId;


function get_db() {
    $mongo = new MongoDB\Client(
      "mongodb://localhost:27017/wai",
      [
          'username' => 'wai_web',
          'password' => 'w@i_w3b',
      ]
    );

    return $mongo->wai;
}

function get_image_base() {
    $db = get_db();
    return $db->imagesv10;
}

function get_user_base() {
    $db = get_db();
    return $db->usersv1;
}

function get_images($page, $useSession, $usePaging) {
    $globalConfig = include('config.php');
    $pageSize = $globalConfig['pageSize'];
    if (!$useSession) {
        $query = [
            '$or' => [
                [
                    'public' => true,
                ]
                ,
                [
                    'public' => false,
                    'author' => $_SESSION['username'],
                ]
            ]
        ];
    } else {
        $queryArray = [];
        foreach ($_SESSION['savedImages'] as $sessionImage) {
            array_push($queryArray, intval($sessionImage));
        }
        $query = [
            'id' => [
                '$in' => $queryArray,
            ]
        ];
    }

    if ($usePaging) {
        $options = [
            'skip' => ($page - 1) * $pageSize,
            'limit' => $pageSize,
        ];
    } else {
        $options = [];
    }
    $db = get_image_base();
    return $db->find($query, $options)->toArray();
}

function add_image($data) {
    $db = get_image_base();
    $db->insertOne($data);
}

function generate_image_name() {
    return md5(time());
}

function is_image_name_used($name) {
    $db = get_image_base();
    $query = [
        'name' => $name,
    ];
    $array = $db->find($query)->toArray();
    if (count($array) > 0) {
        return true;
    } else {
        return false;
    }
}

function prepare_unused_name() {
    $name = generate_image_name();
    while (is_image_name_used($name)) {
        $name = generate_image_name();
    }
    return $name;
}

function get_largest_image_id() {
    $db = get_image_base();
    $query = [];
    $options = [
        'sort' => [
            'id' => -1,
        ]
    ];
    $results = $db->find($query, $options)->toArray();
    if (count($results)) {
        return $results[0]['id'];
    } else {
        return 0;
    }
}

function get_total_images($useSession) {
    return count(get_images(1, $useSession, false));
}

function get_total_image_pages($useSession) {
    $globalConfig = include('config.php');
    return ceil(get_total_images($useSession) / $globalConfig['pageSize']);
}

function is_username_used($name) {
    $db = get_user_base();
    $query = [
        'username' => $name,
    ];
    $array = $db->find($query)->toArray();
    if (count($array) > 0) {
        return true;
    } else {
        return false;
    }
}

function does_password_match_user($name, $password) {
    $db = get_user_base();
    $query = [
        'username' => $name,
    ];
    $array = $db->find($query)->toArray();
    return password_verify($password, $array[0]['password']);
}

function is_email_used($email) {
    $db = get_user_base();
    $query = [
        'email' => $email,
    ];
    $array = $db->find($query)->toArray();
    if (count($array) > 0) {
        return true;
    } else {
        return false;
    }
}

function insert_user($name, $email, $password) {
    $userData = array(
        'username' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
    );

    $db = get_user_base();
    $db->insertOne($userData);
}