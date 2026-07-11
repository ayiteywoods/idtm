<?php

namespace App;

enum UserRole: string
{
    case Admin = 'admin';
    case Student = 'student';
    case Faculty = 'faculty';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Student => 'Student',
            self::Faculty => 'Faculty',
        };
    }
}
