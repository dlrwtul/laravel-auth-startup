<?php

namespace App\Enums;

enum Roles: string
{
    case SUPER_ADMIN = "ROLE_SUPER_ADMIN";
    case ADMIN = "ROLE_ADMIN";
    case SUB_ADMIN = "ROLE_SUB_ADMIN";
    case MANAGER = "ROLE_MANAGER";
}
