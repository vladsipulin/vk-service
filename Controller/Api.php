<?php
namespace Service\Controller\Api;

include_once("Model/Password.php");
include_once("Model/User.php");
include_once("Model/ValidateEmail.php");
include_once("Model/Enum/PasswordCheckStatus.php");
include_once("Controller/Token.php");
include_once("Controller/Database.php");
include_once("Config.php");

use Service\Config;
use Service\Controller\Database;
use Service\Controller\Token;
use Service\Model\User;
use Service\Model\ValidateEmail;
use Service\Model\Password;
use Service\Model\Enum\PasswordCheckStatus;


class Api{
    private static Database $db;

    public static function init(): void {
      Api::$db = new Database(Config::$dbconnection);
    }

    public static function handleRequest(string $path, array $data): mixed{
        switch($path){
            case "/register":
                if(empty($data['email']) || empty($data['password'])) return ['error_msg' => "Форма регистрации не заполнена полностью"];
                $email = $data['email'];
                $password = new Password($data['password']);
                if(ValidateEmail::validateEmail($email)){
                    if($password->getStatus() != PasswordCheckStatus::Weak){
                        return Api::$db->addUser(new User(-1, $email, $password));
                    } else{
                        return ['error_msg' => "Пароль слабый (weak)"];
                    }
                } else{
                    return ['error_msg' => "Неверный формат ввода email"];
                }
            case "/authorize":
                if(empty($data['email']) || empty($data['password'])) {
                    return ['error_msg' => "Форма авторизации не заполнена полностью"];
                }
                else {
                    $email = $data['email'];
                    $password = $data['password'];
                    if (Api::$db->isUserExists($email, $password)) {
                        $token = new Token($data);
                        return ['access_token' => $token->encode()];
                    } else {
                        return ['error_msg' => "Введенный пользователь не существует"];
                    }
                }
            case "/feed":
                if(empty($data['access_token'])){
                    header("HTTP/1.1 401 Unauthorized");
                    return null;
                }
                else {
                    $token = Token::decode($data['access_token']);
                    if($token){
                        $userRes = Api::$db->getUserByEmail($token->getPayload()['email']);
                        if(gettype($userRes) === "object"){
                           header("HTTP/1.1 401 Unauthorized");
                        }
                    } else{
                        header("HTTP/1.1 401 Unauthorized");
                    }
                }
                return null;
            default:
                header("HTTP/1.1 404 Not Found");
                return null;
        }
    }
}