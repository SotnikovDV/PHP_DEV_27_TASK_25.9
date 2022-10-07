<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <title>PHP Gallery</title>
</head>

<body>
    <!-- заголовок страницы -->
    <header>
        <?php
        session_start();
        $user = new User();
        $gallery = new Gallery;
        
        //$usr = $user->addUser('DVS1234', '123456','');

        //$usr = $user->logon('DVS1234', '123456');
        $usr = $user->logonByCookie();


        if (!$user->loged) {
            $login = null;
        } else {
            $login = $usr['login'];
        }

        ?>
        <div class="topnav">
            <div class="page_title">PHP Galery</div>
            <div class="user_name">
                <a href=<?php
                        if (!$login) {
                            echo '"/logon">Войти';
                        } else {
                            echo '"/profile">' . $login;
                        }
                        ?> </a>
            </div>
            <div class="top_menu_btn">
                <a id="btnLink" href="javascript:void(0);" class="dropbtn barbtn">
                    <i class="fa fa-bars barbtn"></i>
                </a>
            </div>
        </div>
        <div id="myDropdown" class="dropdown-content">
            <h3 class="widget-title">Галерея</h3>
            <ul class="widget-list">
                <li><a href="/">Главная</a></li>
                <li><a href="/download">Загрузить фото</a></li>
            </ul>
            <p></p>
            <h3 class="widget-title">Авторизация</h3>
            <ul class="widget-list">
                <?php if ($login) { ?>
                    <li><a href="/profile">Ваш профиль</a></li>
                    <li><a href="/logoff">Выйти</a></li>
                <?php } else { ?>
                    <li><a href="/logon">Войти</a></li>
                    <li><a href="/register">Войти</a></li>
                <?php } ?>
            </ul>
        </div>
    </header>
    <!-- основная страница-->
    <main>
        <?php include $content_view; ?>
    </main>
    <script src="/js/script.js"></script>

</body>

</html>