<?php
if (isset($_POST['submit'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    //echo $login.'<br>';
    //echo $password.'<br>';

    $usr = $user->logon($login, $password);

    if (!$usr) {
        header("Location: logon?error=1");
    } else {
        header("Location: /");
        //exit();        
    }
}
?>
<div class="input-box login-box">
    <div class="login-box-title">Вход</div>
    <?php
    if (key_exists('error', $_REQUEST)) {
    ?>
        <div class="login-box-error">Вы ввели неравильный пароль. Попробуйте еще раз</div>
    <?php
    } else {
    ?>
        <div class="login-box-note">Или <a href="register">зарегиструйтесь</a>, если вы не пользователь галереи</div>
    <?php
    }
    ?>
    <form action="logon" method="post" class="login-form">
        <label for="login">Имя пользователя:</label>
        <input name="login" type="text" placeholder="..." class="inpt" required>
        <label for="password">Пароль:</label>
        <input name="password" type="password" placeholder="..." class="inpt" required>
        <!-- <span></span><div style="margin: 0; font-size: smaller;"><input class="inpt" type="checkbox" onclick="showPassword('password');">  Показать пароль</div> -->
        <input name="submit" type="submit" value="Войти" class="btn">
        <input name="button" type="button" value="Отмена" onclick="location='/'" class="btn">
    </form>
</div>