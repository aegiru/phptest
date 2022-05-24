<?php
$globalConfig = include('config.php');
require_once 'business.php';
require_once 'controller_utils.php';

function index(&$model)
{
    $page = 1;
    if (isset($_GET['page'])) {
        $page = maximum(1, minimum(get_largest_image_id(), $_GET['page']));
    }
    $model['images'] = get_images($page, false, true);
    $model['pages'] = get_pages($page, false);
    $model['page'] = $page;

    login_data($model);

    return 'index';
}

function upload(&$model)
{
    login_data($model);

    return 'upload';
}

function image_upload()
{
    $globalConfig = include('config.php');
    $conversion = include('functionConversion.php');
    if (isset($_FILES['image'])) {
        $error = false;
        $errors = [];
        if (!($_FILES['image']['size'] < $globalConfig['maxFileSize'] * $globalConfig['maxFileMultiplier'] && $_FILES['image']['size'] > $globalConfig['minFileSize'] * $globalConfig['maxFileMultiplier'])) {
            $error = true;
            array_push($errors, 'filesize');
        }

        $imageName = str_replace(' ', '-', strtolower($_FILES['image']['name']));
        $imageExtension = str_replace('.', '', substr($imageName, strrpos($imageName, '.')));
        $containsGoodExtension = false;
        foreach ($globalConfig['allowedExtensions'] as $extension) {
            if ($imageExtension == $extension) {
                $containsGoodExtension = true;
            }
        }
        if (!$containsGoodExtension) {
            $error = true;
            array_push($errors, 'extension');
        }

        if ($error) {
            $stringError = '';
            foreach ($errors as $singleError) {
                $stringError = $stringError . '?' . $singleError . '=1';
            }
            header('Location: /upload' . $stringError);
            exit;
        }

        $newImageName = prepare_unused_name();

        $public = true;
        if ($_POST['privateButton'] === 'true') {
            $public = false;
        }


        $data = [
            'name' => $newImageName,
            'extension' => $imageExtension,
            'title' => $_POST['title'],
            'author' => $_POST['author'],
            'id' => get_largest_image_id() + 1,
            'public' => $public,
        ];

        $newLocation = 'images/original/' . $newImageName . '.' . $imageExtension;
        $thumbnailLocation = 'images/thumb/' . $newImageName . '.' . $imageExtension;
        $watermarkedLocation = 'images/normal/' . $newImageName . '.' . $imageExtension;

        move_uploaded_file($_FILES['image']['tmp_name'], $newLocation);

        $imageConversionFunction = $conversion['input'][$imageExtension];
        $imageOutputFunction = $conversion['output'][$imageExtension];

        $uploadedImage = $imageConversionFunction($newLocation);

        if ($uploadedImage) {
            $imageToScale = $uploadedImage;
            $thumbnailImage = imagescale($imageToScale, 200, 125, IMG_BICUBIC_FIXED);


            if ($thumbnailImage) {
                $imageOutputFunction($thumbnailImage, $thumbnailLocation);
            }

            $font = dirname (__FILE__) . '/font.ttf';

            $imageToWatermark = $imageConversionFunction($newLocation);
            $white = imagecolorallocatealpha($imageToWatermark, 255, 255, 255, 127);
            $black = imagecolorallocatealpha($imageToWatermark, 0, 0, 0, 100);
            imagettftext($imageToWatermark, 200, 0, 200, 200, $black, $font, $_POST['watermark']);
            $imageOutputFunction($imageToWatermark, $watermarkedLocation);


        }

        add_image($data);
//        header('Location: /upload?success=1');
//        exit;
    }
}

function user_signup() {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['userPassword'];
    $passwordRepeat = $_POST['userPasswordRepeat'];
    $error = false;
    $errors=[];
    if (is_username_used($username)) {
        $error = true;
        array_push($errors, 'usernameUsed');
    }

    if (is_email_used($email)) {
        $error = true;
        array_push($errors, 'emailUsed');
    }

    if ($password != $passwordRepeat) {
        $error = true;
        array_push($errors, 'passwordMismatch');
    }

    if ($error) {
        $stringError = '';
        foreach ($errors as $singleError) {
            $stringError = $stringError . '?' . $singleError . '=1';
        }
        header('Location: /register' . $stringError);
        exit;
    }

    insert_user($username, $email, $password);
    header('Location: /login?successfulRegister=1');
    exit;
}

function user_login() {
    $username = $_POST['username'];
    $password = $_POST['userPassword'];
    if (!is_username_used($username)) {
        header('Location: /login?userNotExists=1');
        exit;
    } else {
        if (!does_password_match_user($username, $password)) {
            header('Location: /login?passwordMismatch=1');
            exit;
        } else {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
        }
    }
    header('Location: /index');
    exit;
}

function login(&$model) {
    login_data($model);

    return 'login';
}

function register(&$model) {
    login_data($model);

    return 'register';
}

function user_logout() {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    header('Location: /index');
    exit;
}

function save_images() {
    $checks = $_POST['check'];
    if (!isset($_SESSION['savedImages'])) {
        $_SESSION['savedImages'] = [];
    }
    foreach ($checks as $check) {
        array_push($_SESSION['savedImages'], $check);
    }

    header('Location: /index?page=' . $_POST['formPage']);
    exit;
}

function saved(&$model) {
    login_data($model);

    $page = 1;
    if (isset($_GET['page'])) {
        $page = maximum(1, minimum(get_largest_image_id(), $_GET['page']));
    }
    $model['images'] = get_images($page, true, true);
    $model['pages'] = get_pages($page, true);
    $model['page'] = $page;
    return 'saved';
}

function unsave_images() {
    $checks = $_POST['check'];
    if (!isset($_SESSION['savedImages'])) {
        $_SESSION['savedImages'] = [];
    }

    echo $checks;

    $_SESSION['savedImages'] = array_diff($_SESSION['savedImages'], $checks);

    header('Location: /saved?page=' . $_POST['formPage']);
    exit;
}