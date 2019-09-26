<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, DB, App\User, Cache, Session, Carbon, App\General, App\Classes;


class Pages extends Controller
{
    public function showIndex(){
        $total_students         = User::where('InSchoolFunction','0')->count();
        $total_teachers         = User::where('InSchoolFunction','>','0')->count();
        $total_classes          = Classes::count();
        $school                 = General::getSchoolInfo();
        return view('pages.index')
        ->with('total_students',        $total_students)
        ->with('total_teachers',        $total_teachers)
        ->with('school',                $school)
        ->with('total_classes',         $total_classes);
    }

    public function showLogin(){
        return view('pages.login');
    }

    public function showProfile($id){
        $user                   = User::find($id);
        if(!$user)
            return redirect('/')->with('danger','This user doesn`t exists!');
        if($user->Class !== 0)
        {
            $class              = Classes::find($user->Class);
            $diriginte          = User::where('Class',$user->Class)->where('InSchoolFunction', '1')->first();
            $profesori          = Classes::getTeachers($user->Class);
            $materii            = Classes::getSubjects($user->Class);
        }
        else
        {
            $class              = 0;
            $diriginte          = 0;
            $profesori          = 0;
            $materii            = 0;
        }
        return view('pages.profile')
        ->with('user',                  $user)
        ->with('class',                 $class)
        ->with('diriginte',             $diriginte)
        ->with('profesori',             $profesori)
        ->with('materii',               $materii);
    }
}
