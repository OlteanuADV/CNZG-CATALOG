<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    
    public $table = 'users';
    protected $primaryKey = 'ID';
    protected $fillable = ['LastName', 'FirstName', 'Email'];
    protected $hidden = ['Password'];
    public $timestamps = false;

    public static function getUserFunction($data) {
        switch($data) {
            case '0':
                return 'elev';
            break;
            case '1':
                return 'profesor';
            break;
            case '2':
                return 'director-adjunct';
            break;
            case '3':
                return 'director';
            break;
        }
    }
}
