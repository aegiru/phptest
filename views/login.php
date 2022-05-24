<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Image Gallery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/static/css/styles.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
</head>
<body>
<div id="mainframe">
    <?php include 'includes/navbar.php';?>
    <div class="secinv">
        <div id="maintitle">Login</div>
    </div>
    <div id="upload">
        <form method="post" enctype="multipart/form-data" action="user_login">
            <p align="center">
                <label for="formUsername">Username: </label>
                <input type="text" name="username" id="formUsername"><br>
                <label for="formPassword">Password: </label>
                <input type="password" name="userPassword" id="formPassword"><br>
                <button type="submit">Submit</button>
            </p>
        </form>
    </div>
    <?php if(isset($_GET['successfulRegister']) && $_GET['successfulRegister'] == 1): ?>
        <div class="subtitle">
            Registration successful! Try logging in!
        </div>
    <?php endif ?>
    <?php if((isset($_GET['userNotExists']) && $_GET['userNotExists'] == 1) || (isset($_GET['passwordMismatch']) && $_GET['passwordMismatch'] == 1)): ?>
        <div id="uploadErrors">
            <div class="subtitle">
                Following errors have been found:
            </div>
            <?php if(isset($_GET['userNotExists']) && $_GET['userNotExists'] == 1): ?>
                <div class="subtitle">
                    User doesn't exist.
                </div>
            <?php endif ?>
            <?php if(isset($_GET['passwordMismatch']) && $_GET['passwordMismatch'] == 1): ?>
                <div class="subtitle">
                    Incorrect password.
                </div>
            <?php endif ?>
        </div>
    <?php endif ?>
</div>
</body>
</html>