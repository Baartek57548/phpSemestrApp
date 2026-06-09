<html>
    <head>
        <title><?php echo $title; ?></title>
    </head>

    <body>
        <h1>Hello World App</h1>
        <p>Wybierz element z menu:</p>
        <ul>
            <?php /** @var App\Router $router */ ?>
            <li><a href="<?= $router->generate('home'); ?>">Strona główna</a></li>
            <li><a href="<?= $router->generate('blog', ['id'=>5]); ?>">Blog</a></li>
            <li><a href="<?= $router->generate('dashboard'); ?>">Dashboard</a></li>
            <li><a href="<?= $router->generate('about'); ?>">O nas</a></li>
        </ul>
        <div>
            ERROR RESPONSE!
        </div>
    </body>
</html>

