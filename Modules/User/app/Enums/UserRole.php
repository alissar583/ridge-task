<?php

namespace Modules\User\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Editor = 'editor';
    case User = 'user';
}
