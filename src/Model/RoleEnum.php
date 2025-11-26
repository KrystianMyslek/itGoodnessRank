<?php 

namespace App\Model;

enum RoleEnum: string
{
    case Admin = "ROLE_ADMIN";
    case User = "ROLE_USER";

}