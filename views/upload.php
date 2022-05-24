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
        <div id="maintitle">Image Upload</div>
    </div>
    <div id="upload">
        <form method="post" enctype="multipart/form-data" action="image_upload">
            <p align="center">
                <label for="formAuthor">Author:</label>
                <input type="text" name="author" id="formAuthor" <?php if($loggedin == true): ?>
                    value="<?=$username?>" readonly
                <?php endif ?>>
                <label for="formTitle">Title:</label>
                <input type="text" name="title" id="formTitle"><br>
                <label for="formWatermark">Watermark:</label>
                <input type="text" name="watermark" id="formWatermark"><br>
                <?php if($loggedin == true): ?>
                    <label for="privateRadio">Private: </label>
                    <input type="radio" id="privateRadio" name="privateButton" value="true">
                    <label for="publicRadio">Public: </label>
                    <input type="radio" id="publicRadio" name="privateButton" value="false" checked="checked"><br>
                <?php endif ?>
                <input type="file" name="image">
                <button type="submit">Submit</button>
            </p>
        </form>
    </div>
    <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div id="uploadErrors">
            <div class="subtitle">
                Success!
            </div>
        </div>
    <?php endif ?>
    <?php if((isset($_GET['extension']) && $_GET['extension'] == 1) || (isset($_GET['filesize']) && $_GET['filesize'] == 1)): ?>
        <div id="uploadErrors">
            <div class="subtitle">
                Following errors have been found:
            </div>
            <?php if(isset($_GET['extension']) && $_GET['extension'] == 1): ?>
                <div class="subtitle">
                    Incorrect extension.
                </div>
            <?php endif ?>
            <?php if(isset($_GET['filesize']) && $_GET['filesize'] == 1): ?>
                <div class="subtitle">
                    File too large.
                </div>
            <?php endif ?>
        </div>
    <?php endif ?>
</div>
</body>
</html>