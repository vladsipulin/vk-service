<?php
namespace Service\Controller;

include_once("Model/User.php");

use Service\Model\User;

use mysqli;

class Database{
    private mysqli $db;
    public function __construct(array $dbConnectionInfo){
        $this->db = new mysqli($dbConnectionInfo['host'], $dbConnectionInfo['user'], $dbConnectionInfo['password'], $dbConnectionInfo['db']);
    }

    public function addUser(User $user): mixed{
        $email = $user->getEmail();
        $password = $user->getPassword()->getPassword();
        $prep = $this->db->prepare("SELECT count(*) as `count` FROM `users` WHERE `email` = ?");
        $prep->bind_param('s', $email);
		$prep->execute();
		$count = $prep->get_result()->fetch_assoc()['count'];
        if($count == 0){
            $prep = $this->db->prepare("INSERT INTO `users` (`email`, `password`) VALUES (?, ?)");
            $prep->bind_param('ss', $email, $password);
            $prep->execute();

            if(!empty($this->db->error)){
                return ['error_msg' => $this->db->error];
            }
            $user->setId($this->db->insert_id);

            return ['user_id' => $user->getId(), 'password_check_status' => $user->getPassword()->getStatus()];
        } else {
            return ['error_msg' => "Пользователь с таким email существует"];
        }
    }

    public function getUserByEmail(string $email): mixed {
        $prep = $this->db->prepare("SELECT `id` FROM `users` WHERE `email` = ?");
        $prep->bind_param('s', $email);
        $prep->execute();
		$res = $prep->get_result();
        if($res->num_rows > 0) {
            $findedUser = $res->fetch_assoc();
            return ['user_id' => $findedUser['id']];
        } else{
            return ['error_msg' => "Пользователь с таким email не существует"];
        }
    }

    public function isUserExists(string $email, $password): bool {
        if ($email && $password) {
            $query = "SELECT `password` FROM `users` WHERE `email` = ?";
            $prep = $this->db->prepare($query);
            $prep->bind_param('s', $email);
            $prep->execute();
            $prep->bind_result($dbPass);
            $prep->fetch();
            $prep->close();

            if ($dbPass !== null) {
                if (password_verify($password, $dbPass)) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }
}