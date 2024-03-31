<?php
namespace Service\Model\Enum;

enum PasswordCheckStatus: string{
    case Weak = "weak";
    case Good = "good";
    case Perfect = "perfect";
}