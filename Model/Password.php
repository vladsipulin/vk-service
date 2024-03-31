<?php
namespace Service\Model;

use Service\Model\Enum\PasswordCheckStatus;

class Password implements \JsonSerializable{
    private string $password;
    private PasswordCheckStatus $pswdStatus;

    public function __construct(string $password){
        Password::setPassword($password);
        $this->pswdStatus = Password::determineStatus($password);
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function getStatus(): PasswordCheckStatus{
        return $this->pswdStatus;
    }

    public function setPassword(string $password): void{
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hashed_password;
    }

    public function setStatus(PasswordCheckStatus $pswdStatus): void{
        $this->pswdStatus = $pswdStatus;
    }

    public function jsonSerialize(): mixed{
        return get_object_vars($this);
    }

    public static function determineStatus(string $password): PasswordCheckStatus{
        if(strlen($password) >= 8 && preg_match("/[a-z]/", $password) && preg_match("/[A-Z]/", $password) && preg_match("/\d/", $password) && strlen($password) <= 255){
            if(strlen($password) >= 12 && preg_match("/[-_!@#$%^&*()—+;:,.\/?\\|`~\[\]{}]/", $password)){
                return PasswordCheckStatus::Perfect;
            } else{
                return PasswordCheckStatus::Good;
            }
        } else {
            return PasswordCheckStatus::Weak;
        }
    }
}