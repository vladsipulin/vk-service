<?php
namespace Service\Model;

use Service\Model\Password;

class User implements \JsonSerializable{
    private int $id;
    private string $email;
    private Password $password;

    public function __construct(int $id, string $email, Password $password){
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function getPassword(): Password{
        return $this->password;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setEmail(string $email): void{
        $this->email = $email;
    }

    public function setPassword(Password $password): void{
        $this->password = $password;
    }

    public function jsonSerialize(): mixed{
        return get_object_vars($this);
    }
}