<div class="navbar" id="topNavbar">
    <a href="/">Home</a>
    <a href="/upload">Upload</a>
    <a href="/saved">Saved</a>
    <?php if($loggedin != true): ?>
        <a href="/login">Login</a>
        <a href="/register">Registration</a>
    <?php else: ?>
        <a href="/logout">Log out</a>
    <?php endif ?>
</div>