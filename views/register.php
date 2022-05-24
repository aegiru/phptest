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
        <div id="maintitle">Register</div>
    </div>
    <div id="upload">
        <form method="post" enctype="multipart/form-data" action="user_signup">
            <p align="center">
                <label for="formUsername">E-Mail: </label>
                <input type="email" name="email" id="formEmail"><br>
                <label for="formUsername">Username: </label>
                <input type="text" name="username" id="formUsername"><br>
                <label for="formTitle">Password: </label>
                <input type="password" name="userPassword" id="formPassword"><br>
                <label for="formTitle">Repeat password: </label>
                <input type="password" name="userPasswordRepeat" id="formPasswordRepeat"><br>
                <button type="submit">Submit</button>
            </p>
        </form>
    </div>
    <?php if((isset($_GET['usernameUsed']) && $_GET['usernameUsed'] == 1) || (isset($_GET['emailUsed']) && $_GET['emailUsed'] == 1) || (isset($_GET['passwordMismatch']) && $_GET['passwordMismatch'] == 1)): ?>
        <div id="uploadErrors">
            <div class="subtitle">
                Following errors have been found:
            </div>
            <?php if(isset($_GET['usernameUsed']) && $_GET['usernameUsed'] == 1): ?>
                <div class="subtitle">
                    Username already used.
                </div>
            <?php endif ?>
            <?php if(isset($_GET['emailUsed']) && $_GET['emailUsed'] == 1): ?>
                <div class="subtitle">
                    Email already used.
                </div>
            <?php endif ?>
            <?php if(isset($_GET['passwordMismatch']) && $_GET['passwordMismatch'] == 1): ?>
                <div class="subtitle">
                    Mismatch between passwords.
                </div>
            <?php endif ?>
        </div>
    <?php endif ?>
</div>
</body>
</html>