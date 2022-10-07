<?php
if (isset($_POST['submit'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];

    // проверяем совпадение паролей
    if ($password !== $password2) {
        header("Location: register?notequal");
        exit();        
    }

    $usr = $user->addUser($login, $password, $email);

    if (!$usr) {
        header("Location: register?error");
        exit();        
    } else {
        $usr = $user->logon($login, $password);
        header("Location: /");
        exit();        
    }
}
?>

<div class="input-box login-box">
    <div class="login-box-title">Регистрация</div>
    <?php
    if (key_exists('notequal', $_REQUEST)) {
    ?>
        <div class="login-box-error">Ошибка повторного ввода пароля...</div>
        <?php
    } else {
        if (key_exists('error', $_REQUEST)) {
        ?>
            <div class="login-box-error">Регистрация не удалась. Попробуйте еще раз</div>
        <?php
        } else {
        ?>
            <div class="login-box-note">Или <a href="logon">войдите</a>, если Вы уже регистрировались</div>
    <?php
        }
    }
    ?>
    <form action="register" method="post" class="login-form">
        <label for="login">Имя пользователя:</label>
        <input name="login" type="text" placeholder="..." class="inpt" value="<?= (key_exists('login', $_REQUEST)) ? $_REQUEST['login'] : null ?>" required>
        <label for="password">Пароль:</label>
        <input name="password" type="password" placeholder="..." class="inpt" required>
        <label for="password2">Повторите пароль:</label>
        <input name="password2" type="password" placeholder="..." class="inpt" required>
        <label for="email">Эл.почта:</label>
        <input name="email" type="email" placeholder="user@email.ru" class="inpt">
        <input name="submit" type="submit" value="Регистрация" class="btn">
        <input name="button" type="button" value="Отмена" onclick="location='/'" class="btn">
    </form>
</div>