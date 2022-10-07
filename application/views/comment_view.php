<?php
if (isset($_POST['submit'])) {
    $errors = [];

    
        // перечитаем коллекцию комментариев
        //$gallery->getPhotos(true);
 

    if (count($errors) > 0) {
        //header("Location: logon?error=1");
        echo '<div class="input-box file-box">';
        echo '<div class="login-box-title file-box">При добавлении/изменении/удалении комментария произошли следующие ошибки:</div><br>';
        echo '<div class="login-box-note" style="text-align: left;">';
        foreach ($errors as $error){
            echo $error.'<br>';
        }
        echo '</div>';
        echo '</div>';
    } else {
        //header("Location: /");
        //exit();
        echo '<div class="input-box file-box">';
        echo '<div class="login-box-title file-box">Комментарий успешно добавлен/изменен</div><br>';
        echo '<div class="login-box-note">Вы можете добавить/изменить еще комментарий или <a href="/">перейти в галерею</a></div>';
        echo '</div>';

    }
} else {
    if (key_exists('action', $_GET)) {
        $action = $_GET['action'];
        $pid = $_GET['pid'];
        if (key_exists('id', $_GET)) {
            $id = $_GET['id'];
        } else {
            $id = null;
        }
        switch ($action) {
            case 'add':
                $title = 'Добавление ';
                break;
            case 'delete':
                $title = 'Удаление';
                break;
            case 'edit':
                $title = 'Исправление';
                break;
        }
    } 
        


}
?>
<div class="input-box file-box">
    <div class="login-box-title"><?= $title ?> комментария</div>
    <?php
    if (key_exists('error', $_REQUEST)) {
    ?>
        <div class="login-box-error">Ошибка комментирования. Попробуйте еще раз</div>
    <?php
    } else {
    ?>
        <div class="login-box-note">
            Максимальный размер комментария: <b><?= Config::MAX_COMMENT_SIZE ?> символов</b><br>
        </div>

    <?php
    }
    ?>
    <form action="download" method="post" class="login-form" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?= $maxFileSize ?>">
        <label for="comment">Комментарий:</label>
        <input name="comment" type="text" placeholder="..." class="inpt">
        <!-- <p><textarea name="comment" cols="40" rows="3" class="inpt"></textarea></p> -->
        <input name="submit" type="submit" value="Загрузить" class="btn">
        <input name="button" type="button" value="Отмена" onclick="location='/'" class="btn">
    </form>
</div>