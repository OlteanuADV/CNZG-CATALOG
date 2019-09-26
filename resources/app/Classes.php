<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User, App\Subjects;
class Classes extends Model
{
    public $table = 'classes';
    protected $primaryKey = 'ID';
    protected $fillable = ['Number', 'Character'];
    public $timestamps = false;

    public static function getTeachers($classid){
        $clasa = Classes::find($classid);
        $explodat = explode(',',$clasa->Teachers);
        $teachers = [];
        foreach($explodat as $a){
            array_push($teachers, User::find($a));
        }
        return $teachers;
    }

    public static function getSubjects($classid){
        $teachers = self::getTeachers($classid);
        $subjects = [];
        foreach($teachers as $a){
            array_push($subjects, Subjects::find($a->Subject));
        }
        return $subjects;
    }
}
