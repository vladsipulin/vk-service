<?php
namespace Service\Model;

class Error implements \JsonSerializable{

    private string $msg;

    public function __construct(string $msg){
        $this->msg = $msg;
    }

    public function getMsg(): string{
        return $this->msg;
    }

    public function setMsg(string $msg): void{
        $this->msg = $msg;
    }

    public function jsonSerialize(): mixed{
        return get_object_vars($this);
    }
}