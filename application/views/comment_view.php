<?php
/* ------------------------ Обработка формы ------------------ */
if (isset($_POST['submit'])) {
    $errors = [];
    
    $action = $_POST['action'];
    $pid = $_POST['pid'];
    $id = $_POST['id'];
    $comment = $_POST['comment'];

    switch ($action) {
        case 'add': 
            $result = $gallery->addComment($pid, $comment, $user->user_id);
            if (!$result){
                $error[] = 'Ошибка добавления комментария в базу данных: ' . implode($gallery->lastErrors);
            }
            $title = 'Добавление ';
                $btn   = 'Добавить';
        case 'edit':    
            $result = $gallery->editComment($pid, $comment);
            if (!$result){
                $error[] = 'Ошибка сохранения комментария в базу данных: ' . implode($gallery->lastErrors);
            }
            $title = 'Исправление';
                $text = null;
        case 'delete':
            $result = $gallery->deleteComment($pid);
            if (!$result){
                $error[] = 'Ошибка удаления комментария из базы данных: ' . implode($gallery->lastErrors);
            }
            $title = 'Удаление';
                $text = null;
    }

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
    /* ------------------------ Обработка параметров GET ------------------ */
    if (key_exists('action', $_GET)) {
        $action = $_GET['action'];
        $pid = $_GET['pid'];

        if (key_exists('id', $_GET)) {
            $id = $_GET['id'];
            $comment = $gallery->getCommentByID($id);
        } else {
            $id = null;
        }
        switch ($action) {
            case 'add':
                $title = 'Добавление ';
                $btn   = 'Добавить';
                $text = null;
                break;
            case 'delete':
                $title = 'Удаление';
                $text = $comment['comment'];
                $btn   = 'Удалить';
                break;
            case 'edit':
                $title = 'Исправление';
                $text = $comment['comment'];
                $btn   = 'Сохранить';
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
        $photo = $gallery->getPhotoByID($pid);
        echo '<div class="login-box-note">';
        echo '<img class="demo cursor" src="' . Config::PHOTO_DIR . '/' . $photo['id'] . '.' . $photo['type'] . '" style="width:90%" alt="' . $photo['title'] . '">';
        echo '</div>';
        if ($action !== 'delete'){
?>

        <div class="login-box-note">
            Максимальный размер комментария: <b><?= Config::MAX_COMMENT_SIZE ?> символов</b><br>
        </div>

    <?php
        }
    }
    ?>
    <form action="comment" method="post" class="login-form">
        <input type="hidden" name="action" value="<?=$action?>">
        <input type="hidden" name="pid" value="<?=$pid?>">
        <input type="hidden" name="id" value="<?=$id?>">
        <label for="comment">Комментарий:</label>
        <input name="comment" type="text" placeholder="..." class="inpt" value="<?=$text?>">
        <!-- <p><textarea name="comment" cols="40" rows="3" class="inpt"></textarea></p> -->
        <input name="submit" type="submit" value="<?=$btn?>" class="btn">
        <input name="button" type="button" value="Отмена" onclick="location='/'" class="btn">
    </form>
</div>