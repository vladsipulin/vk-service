<?php
namespace Service\Controller;

include_once("Config.php");

use Service\Config;

class Token{
    private array $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    private array $payload;

    public function __construct(array $payload){
        $this->payload = $payload;
    }

    public function getPayload(): array{
        return $this->payload;
    }

    public function setPayload(array $payload): void{
        $this->payload = $payload;
    }

    public function encode(): string{
        $header = base64_encode(json_encode(($this->header)));
        $payload = base64_encode(json_encode(($this->payload)));
        $signature = hash_hmac('sha256', $header.".".$payload, Config::$token);
        echo "BEFORE Computed Hash: " . $signature;
        return $header.".".$payload.".".$signature;
    }

    public static function decode(string $token): mixed{
        $exp_token = explode(".", $token);
        $header = $exp_token[0];
        $payload = $exp_token[1];
        $signature = $exp_token[2];
        #отладка
        #echo "source " . $header . "  " . $payload . "  " . $signature . "  " . "\n";
        #echo "Signature: $signature\n";
        #echo "AFTER Computed Hash: " . hash_hmac('sha256', $header.".".$payload, Config::$token);

        if(hash_hmac('sha256', $header.".".$payload, Config::$token) == $signature){
            #echo "i did it";
            return new Token(json_decode(base64_decode($payload), true));
        } else {
            return null;
        }
    }
}