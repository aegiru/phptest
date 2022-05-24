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
        <div id="maintitle">Gallery</div>
    </div>
    <form method="post" action="unsave_images">
        <?php if(count($images)): ?>
            <input type="text" style="display: none;" name="formPage" value="<?=$page?>"/>
            <div id="gallery">
                <?php foreach ($images as $image): ?>
                    <div class="galleryItem">
                        <a href="../images/normal/<?=$image['name']?>.<?=$image['extension']?>" class="itemLink">
                            <img src="../images/thumb/<?=$image['name']?>.<?=$image['extension']?>" class="itemImage"/>
                        </a>
                        <div class="itemBox">
                            <div class="itemInfo">
                                <span class="itemTitle"><?=$image['title']?></span>
                                <span class="itemAuthor"><?=$image['author']?></span>
                            </div>
                            <div class="itemSelector">
                                <input type="checkbox" name="check[]" value="<?=$image['id']?>">
                            </div>
                        </div>
                    </div>
                <?php endforeach?>
            </div>
            <div class="subtitle">
                <button type="submit">Forget chosen</button>
            </div>
        <?php endif ?>
    </form>
    <div id="links">
        <?php if(count($pages)): ?>
            <?php foreach ($pages as $page): ?>
                <?php if($page != '...'): ?>
                    <a href="saved?page=<?=$page?>">
                        <div class="subtitle">
                            <?=$page?>
                        </div>
                    </a>
                <?php else: ?>
                    <div class="subtitle">
                        <?=$page?>
                    </div>
                <?php endif ?>
            <?php endforeach?>
        <?php endif ?>
    </div>
</div>
</body>
</html>