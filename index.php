<?php
session_start();
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset=utf-8>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <meta name="description" content="Upload and share files anonymously for free."/>
    <meta property=og:type content=website />
    <meta property=og:url content="https://pomfe.co" />
    <meta property=og:title content="Pomfe.co - File Hosting" />
    <meta property=og:site_name content=Pomfe.co />
    <meta property=og:locale content=en-US />
    <meta property=og:image content="https://pomfe.co/kyubey.jpg">
    <title>&middot; Pomfe.co &middot;</title>
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="css/style.css">
    <script src="pomf.min.js"></script>
</head>

<?php
//echo $_COOKIE['IsAnon'];
$max_upload = 100;
$_SESSION['Max_Upload'] = 100;
if (isset($_SESSION['level'])) {
    $rank = $_SESSION['level'];
    if ($rank == "1") {
        // Admin
        $max_upload = 1000;
        $_SESSION['Max_Upload'] = 1000;
    } elseif ($rank == "0.1") {
        // Donor
        $max_upload = 400;
        $_SESSION['Max_Upload'] = 400;
    } elseif ($rank == "0") {
        // User
        $max_upload = 200;
        $_SESSION['Max_Upload'] = 200;
    }
}
?>

<body>
<div class="container">
    <div class="jumbotron">
        <h1>Ohayō!</h1>
        <p class="lead"><?= $max_upload ?>MiB - Max Upload/File</p>
        <form id="upload-form" enctype="multipart/form-data" method="post" action="upload.php?output=html">
            <button id="upload-btn" class="btn" type="button">Select or drop file(s)</button>
            <input type="file" id="upload-input" name="files[]" multiple="multiple" data-max-size="<?= $max_upload ?>MiB"/>
            <?php
            if (isset($_SESSION['email'])) {
                ?>
                <br>
                <h3 class="alert alert-info">
                    <input id="anonTrue" type="radio" name="isAnonymousRadio" value="True" checked> Post this anonymously<br>
                    <input id="anonFalse" type="radio" name="isAnonymousRadio" value="False"> Post this through my account<br>
                </h3>
                <br>
                <?php
            }
            ?>
            <input type="submit" value="Submit"/></form>
        <ul id="upload-filelist"></ul>

        <div class="footer">
            <?php include('global/footer.html'); ?>
        </div>

        <p class="alert alert-info">
            <strong>Want to see all of your files? Delete them? Or maybe you want a higher upload limit?</strong>
            <br> Well, now you can! We have just recently implemented a user panel, which allows you to do just these things with <strong>many</strong> more features on their way.<br> 
            <br>So, what are you waiting for? Sign up now!
            <br><a href="https://cloud.pomfe.co/login.php" target="_BLANK">Log in</a> | <a href="https://cloud.pomfe.co/register.php" target="_BLANK">Register</a></div>

</body>

</html>
