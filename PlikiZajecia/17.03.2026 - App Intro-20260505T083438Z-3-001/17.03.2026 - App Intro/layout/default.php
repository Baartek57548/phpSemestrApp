<html>
    <head>
        <title><?php echo $title; ?></title>
    </head>

    <body>
        <h1>Hello World App</h1>
        <p>Wybierz element z menu:</p>
        <ul>
            <li><a href="/?page=homepage">Strona główna</a></li>
            <li><a href="/?page=about">O nas</a></li>
        </ul>
        <div>
            <?php echo $content; ?>
        </div>
    </body>
</html>

