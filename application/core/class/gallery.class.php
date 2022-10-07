<?php

require_once 'mysql.class.php';
require_once 'config.class.php';
require_once 'user.class.php';

class Gallery
{

    public $lastErrors;

    private $photos;  // коллекция фотографий
    private $comments;  // комментарии

    // ------------------- Фотографии -----------------------

    // загрузка таблицы фотографий из БД
    public function getPhotos($reload = false)
    {

        if (!$this->photos or $reload) {

            $this->lastErrors = [];

            // подключаемся к БД
            $db = new MySQL(Config::DB_SERVER, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);
            if (!$db) {
                $this->lastErrors[] = 'Ошибка подключения к базе данных: ';
                return null;
            }

            // запрашиваем список фотографий
            $this->photos = $db->select('SELECT * FROM photos');


            if ($db->getError()) {
                $this->lastErrors[] = $db->getError()['message'];
            }

            // Закрываем БД
            $db->closeConnection();

            return $this->photos;
        } else {
            return $this->photos;
        }
    }

    //Добавление новой фотографии
    // $fileName - путь к файлу в папке загрузки
    // $photoTitle - описание фотографии
    // При успехе возвращает ID файла в БД
    // в случае неудачи - false
    public function addPhoto($fileName, $photoTitle, $user_id)
    {

        $this->lastErrors = [];

        $fileParts = pathinfo($fileName);
        $fileExt = $fileParts['extension'];

        // подключаемся к БД
        $db = new MySQL(Config::DB_SERVER, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);
        if (!$db) {
            $this->lastErrors[] = 'Ошибка подключения к базе данных: ';
            return false;
        }

        // запись в БД
        $sql = 'INSERT INTO photos (filename, title, owner, type) values ("' . mysqli_real_escape_string($db->db_connect, basename($fileName)) . '", "' . mysqli_real_escape_string($db->db_connect, $photoTitle) . '", ' . $user_id . ', "' . $fileExt . '");';
        $result = $db->update($sql);

        // запрос последнего ID
        if ($result) {
            $sql = 'SELECT MAX(id) max_id FROM photos;';
            $query = $db->select_row($sql);

            if (!$query) {
                $this->lastErrors[] = $db->getError()['message'];
                return false;
            } else {
                $id = $query['max_id'];
            }
        } else {
            if ($db->getError()) {
                $this->lastErrors[] = $db->getError()['message'];
                return false;
            }
        }

        // Закрываем БД
        $db->closeConnection();

        if (!$result) {
            return false;
        } else {

            $fileNewName = Config::PHOTO_DIR . '/' . $id . '.' . $fileExt;

            // Переносим файл в папку для фотографий с переименованием в ID
            if (!copy($fileName, $fileNewName)) {
                $this->lastErrors[] = $db->getError()['message'];
                return false;
            }

            return $id;
        }
    }

    // поиск фото в масиве по ID
    public function getPhotoByID($id)
    {
        $this->lastErrors = [];

            // подключаемся к БД
            $db = new MySQL(Config::DB_SERVER, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);
            if (!$db) {
                $this->lastErrors[] = 'Ошибка подключения к базе данных: ';
                return null;
            }

            // запрашиваем фотографию
            $photo = $db->select_row('SELECT * FROM photos WHERE id = '.$id);

            // Закрываем БД
            $db->closeConnection();

            if (!$photo) {
                $this->lastErrors[] = $db->getError()['message'];
                return null;
            } else {
                return $photo;
            }
    }

    // Удаление фотографии
    public function deletePhoto($id) {
        $this->lastErrors = [];

        $photo = $this->getPhotoByID($id);

        // Удаляем в БД
        // подключаемся к БД
        $db = new MySQL(Config::DB_SERVER, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);
        if (!$db) {
            $this->lastErrors[] = 'Ошибка подключения к базе данных: ';
            return false;
        }

        // запись в БД
        $sql = 'DELETE FROM photos WHERE id = ' . $id . ';';
        $result = $db->update($sql);

        if (!$result) {
            $this->lastErrors[] = 'Проблема удаления в БД. ' . $db->getError()['message'];
            return false;
        }
    
        
        // Удаляем файл
        $fileName = Config::PHOTO_DIR . '/' . $id . '.' . $photo['type'];

        if (file_exists($fileName)) {
            $result = unlink($fileName);
            if (!$result) {
                $this->lastErrors[] = 'Ошибка удаления файла с ресурса [ '.$fileName.' ]';
            }
        }

        return true;
    }

    // ------------------- Фотографии -----------------------
}
