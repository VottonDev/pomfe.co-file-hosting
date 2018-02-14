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
  <title>&middot; Pomfe.co &middot;</title>
  <link rel="shortcut icon" href=favicon.ico type="image/x-icon">
  <link rel="stylesheet" href="css/style.css">
    <script src="pomf.min.js"></script>
</head>

<body>
<div class="container">
    <div class="jumbotron">
        <h1>Ohayō!</h1>
        <p class="lead">100MiB - Max Upload/File</p>
        <form id="upload-form" enctype="multipart/form-data" method="post" action="upload.php?output=html">
            <button id="upload-btn" class="btn" type="button">Select or drop file(s)</button>
            <input type="file" id="upload-input" name="files[]" multiple="multiple" data-max-size="100MiB"/>
            <input type="submit" value="Submit"/></form>
        <ul id="upload-filelist"></ul>

        <div class="footer">
            <?php include('global/footer.html'); ?>
        </div>

        <p class="alert alert-info">
            <strong>Pomfe.co is a free service, but our hosting bill isn't</strong> &mdash; we need exactly 35$ each month to pay for our bills and keep this service free forever. As a donator, you'll receive 100% transparency on the money you donate, whether it's upgrades, monthly bills or other expenses. Thanks a lot!
            <span class="donate-btns"><a class="donate-btn donate-bitcoin" href="bitcoin:1Jx1PSYdqwrhtYGhUvVvYZRfKScHQ9VLRH?label=tpb.lc&amp;message=Hosting%20Bills" target="_BLANK"><span class="icon icon-bitcoin"></span> Bitcoin</a><a class="donate-btn donate-patreon" href="https://www.patreon.com/Votton" target="_BLANK"><span class="icon icon-patreon"></span> Patreon</a></div>

</body>

</html>