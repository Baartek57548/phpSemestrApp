<html>
    <head>
        <title><?php echo $title; ?></title>
    </head>

    <body>
        <h1>Hello World App</h1>
        <p>Wybierz element z menu:</p>
        <ul>
            <li><a href="/">Strona główna</a></li>
            <li><a href="/articles">Blog</a></li>
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/about">O nas</a></li>
        </ul>
        <div>
            <?php echo $content; ?>
        </div>
    </body>
</html>

