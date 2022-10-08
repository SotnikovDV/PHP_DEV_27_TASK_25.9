<?php

// считываем галерею
$photos = $gallery->getPhotos(false);  // без принудительного обновления

if (!$photos) {
    echo '<div class="input-box file-box">';
    echo '<div class="login-box-title file-box">Галерея пуста</div><br>';
    echo '<div class="login-box-note"><a href="download">Загрузите файлы фотографий</a> в галерею...</div>';
    echo '</div>';
    exit;
}
?>
<div class="mainrow">
    <div class="contentbox">
        <!-- Container for the image gallery -->
        <div class="container">

            <?php
            foreach ($photos as $photo) {
                echo '<div class="mySlides" id="' . $photo['id'] . '">';
                echo '<div class="numbertext">' . $photo['id'] . ' / 6</div>';
                echo '<img src="' . Config::PHOTO_DIR . '/' . $photo['id'] . '.' . $photo['type'] . '" style="width:100%" alt="' . $photo['title'] . '">';
                echo '</div>';
            }
            ?>
            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
            <!-- Image text -->
            <div class="caption-container">
                <p id="caption"></p>
            </div>
        </div>

        <!-- Thumbnail images -->
        <div class="row">

            <?php
            $i = 1;
            foreach ($photos as $photo) {
                echo '<div class="column">';
                echo '<img class="demo cursor" src="' . Config::PHOTO_DIR . '/' . $photo['id'] . '.' . $photo['type'] . '" style="width:100%" onclick="currentSlide(' . $i . ')" alt="' . $photo['title'] . '">';
                echo '</div>';
                $i++;
            }
            ?>
            <!-- </div> -->
        </div>
    </div>
    <div class="rightcolumn">
        <div class="card">
            <div class="card-title">Фото</div>
            <div class="card-content">
                <ul class="card-content-list">
                    <li><a href="/download">Загрузить фото</a></li>
                    <li><a href="javascript: deleteSlide();">Удалить фото</a></li>
                    <li><a href="javascript: doComment('add');">Добавить комментарий</a></li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-title">Комментарии</div>

            <?php
            // считываем комментарии
            $comments = $gallery->getComments(false);  // без принудительного обновления
            $i = 1;
            foreach ($comments as $coment) {
                echo '<div class="card-comment photo'.$coment['image'].'" id=com' . $coment['id'] . '>';
                echo '<p><img src="/images/avatar.png" alt="Avatar" style="width:30px">';
                echo ' <span>' . $coment['username'] . '</span>';
                echo '<a href="/comment?pid='.$coment['image'].'&action=delete&id='.$coment['id'].'" class="card-comment-btn"> X </a>';
            
                /*echo '<div class="top_menu_btn">';
                echo '<a id="btnLink" href="javascript:void(0);" class="dropbtn barbtn">';
                echo '<i class="fa fa-bars barbtn"></i></a></div>';*/

                echo '</p>';
                echo '<p><a href="/comment?pid='.$coment['image'].'&action=edit&id='.$coment['id'].'">' . $coment['comment'] . '</a></p>';
                echo '<hr>';
                echo '</div>';
                
                $i++;
            }
            ?>

        </div>
    </div>
</div>


<script src="/js/slides.js"></script>