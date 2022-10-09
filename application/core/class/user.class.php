<?php

require_once 'mysql.class.php';

class User
{

    public $lastErrors;

    public $user_id;
    public $user_login;
    public $user_password;
    public $user_hash;
    public $user_email;
    public $user_ip;

    public $loged;

    function __construct()
    {
        $this->loged = false;
    }

    // проверяем логин
    public function checkLogin($userName)
    {
        $this->lastErrors = [];

        if (!preg_match("/^[a-zA-Z0-9]+$/", $userName)) {
            $this->lastErrors[] = "Логин может состоять только из букв английского алфавита и цифр";
        }
        if (strlen($userName) < 3 or strlen($userName) > 30) {
            $this->lastErrors[] = "Логин должен быть не меньше 3-х символов и не больше 30";
        }

        if (count($this->lastErrors) == 0) {
            return true;
        } else {
            return false;
        }
    }

    // проверяем пароль
    public function checkPassword($userPassw)
    {
        $this->lastErrors = [];

        if (!preg_match("/^[a-zA-Z0-9]+$/", $userPassw)) {
            $this->lastErrors[] = "Пароль может состоять только из букв английского алфавита и цифр";
        }
        if (strlen($userPassw) < 3 or strlen($userPassw) > 30) {
            $this->lastErrors[] = "Пароль должен быть не меньше 3-х символов и не больше 30";
        }

        if (count($this->lastErrors) == 0) {
            return true;
        } else {
            return false;
        }
    }

    // генерация случайной строки для хэша
    private function generateCode($length = 6)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
        }
        return $code;
    }

    // добавление пользователя в БД
    // возвращает запись из таблицы users или null
    // пароль передается нехешированный
    public function addUser($userName, $userPassw, $email)
    {

        $this->lastErrors = [];

        // проверка имени пользователя
        if (!$this->checkLogin($userName)) {
            return null;
        }

        // проверка пароля
        if (!$this->checkPassword($userName)) {
            return null;
        }

        $ushash = $this->generateCode();
        $pass = password_hash($userPassw, PASSWORD_DEFAULT);
        $ip = $_SERVER['REMOTE_ADDR'];


        // подключаемся к БД
        $db = new MySQL(Config::DB_SERVER, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);
        if (!$db) {
            $this->lastErrors[] = 'Ошибка подключения к базе данных: ';
            return null;
        }

        $sql = 'INSERT INTO users (login, PASSWORD, HASH, email, ip ) VALUES ("'
            . mysqli_real_escape_string($db->db_connect, $userName) . '", "'
            . mysqli_real_escape_string($db->db_connect, $pass) . '", "'
            . mysqli_real_escape_string($db->db_connect, $ushash) . '", "'
            . mysqli_real_escape_string($db->db_connect, $email) . '", "'
            . mysqli_real_escape_string($db->db_connect, $ip) . '");';

        $result = $db->update($sql);

        // Закрываем БД
        $db->closeConnection();

        if (!$result) {
            $this->lastErrors[] = 'Ошибка добавления пользователя: ' . $db->getError()['message'];
            return null;
        } else {
            // Ищем пользователя
            $usr = $this->userExists($userName);
            return true;
        }
    }

    // проверка на существование такого пользователя в БД
    // возвращает запись из таблицы users или null;
    public function userExists($userName)
    {
        $this->lastErrors = [];

        // подключаемся к БД
        $db = new MySQL(Config::DB_SERVER, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);
        if (!$db) {
            $this->lastErrors[] = 'Ошибка подключения к базе данных: ';
            return null;
        }

        // запрашиваем пользователя в БД
        $usr = $db->select_row('SELECT * FROM users WHERE login = "' . mysqli_real_escape_string($db->db_connect, $userName) . '"');

        // Закрываем БД
        $db->closeConnection();

        if (!$usr) {
            $this->lastErrors[] = 'Ошибка проверки существования пользователя: ' . $db->getError()['message'];
            return null;
        } else {
            return $usr;
        }
    }

    // считывание пользователя из БД по ID
    // возвращает запись из таблицы users или null;
    public function getUserByID($id)
    {
        $this->lastErrors = [];

        // подключаемся к БД
        $db = new MySQL(Config::DB_SERVER, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);
        if (!$db) {
            $this->lastErrors[] = 'Ошибка подключения к базе данных: ';
            return null;
        }

        // запрашиваем пользователя в БД
        $usr = $db->select_row('SELECT * FROM users WHERE id = ' . mysqli_real_escape_string($db->db_connect, $id));

        // Закрываем БД
        $db->closeConnection();

        if (!$usr) {
            $this->lastErrors[] = 'Ошибка считывания пользователя по ID: ';
            return null;
        } else {
            return $usr;
        }
    }

    // вход пользователя по имени и паролю
    // возвращает запись из таблицы users или null
    // пароль передается нехешированный
    public function logon($userName, $userPassw)
    {

        $pass = password_hash($userPassw, PASSWORD_DEFAULT);

        $this->lastErrors = [];

        // Ищем пользователя
        $usr = $this->userExists($userName);


        if (!$usr) {
            $this->lastErrors[] = 'Нет такого пользователя в базе данных:';
            // надо бы на ошибки проверить
            return null;
        }

        // проверяем совпадение пароля с хэшем
        if (password_verify($userPassw, $usr['password'])) {

            $this->user_id = $usr['id'];
            $this->user_login = $usr['login'];
            $this->user_password = $usr['password'];
            $this->user_hash = $usr['hash'];            // теоретически надо каждый раз перезаписывать в БД и в cookie
            $this->user_email = $usr['email'];
            $this->user_ip = $usr['ip'];

            $this->loged = true;

            // Ставим куки
            setcookie("id", $usr['id'], time()+60*60*24*30, "/");
            setcookie("hash", $usr['hash'], time()+60*60*24*30, "/", null, null, true); // httponly !!! 
            //var_dump($usr);

            return $usr;
        }
    }

    // попытка автоматического входа пользователя по ID в COOKIE
    // возвращает запись из таблицы users или null
    public function logonByCookie()
    {

        $this->lastErrors = [];

        if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
            
            $id = $_COOKIE['id'];
            $hash = $_COOKIE['hash'];

            // запрашиваем пользователя в БД по ID
            $usr = $this->getUserByID($id);

            if (!$usr) {
                $this->lastErrors[] = 'Ошибка считывания пользователя по ID';
                return null;
            } else {
                if (($usr['id'] === $id) and ($usr['hash'] === $hash)){
                    $this->user_id = $usr['id'];
                    $this->user_login = $usr['login'];
                    $this->user_password = $usr['password'];
                    $this->user_hash = $usr['hash'];
                    $this->user_email = $usr['email'];
                    $this->user_ip = $usr['ip'];

                    $this->loged = true;

                } else {
                    $this->lastErrors[] = 'Hash или ID пользователя в cookie не совпадают с базой данных';
                    // Удаляем куки
                    setcookie("id", "", time() - 3600*24*30*12, "/");
                    setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
                    return null;
                }

                return $usr;
            }
        } else {
            $this->lastErrors[] = 'Включите cookie';
        }
    }

    // процедура выхода
    public function logoff()
    {
        $this->user_id = null;
        $this->user_login = null;
        $this->user_password = null;
        $this->user_hash = null;
        $this->user_email = null;
        $this->user_ip = null;

        $this->loged = false;

        // Удаляем куки
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
    }
}
