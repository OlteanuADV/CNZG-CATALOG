<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, DB, App\User, Cache, Session, Carbon, App\General, App\Grades;

class Requests extends Controller
{
    public function postLogin(Request $r){
        $r->validate([
            'password' => 'required',
            'lastname' => 'required',
            'firstname' => 'required'
        ]);
        $p = $r->input('password');
        $l = $r->input('lastname');
        $f = $r->input('firstname');

        $user = User::where('LastName', $l)->where('FirstName',$f)->where('Password',$p)->first();
        if(!$user)
            return json_encode(['message'=>'Utilizatorul nu a fost gasit!']);
        else
        {
            Auth::loginUsingId($user->ID);
            return json_encode(['success' => 1,'message'=>'Conectare cu succes!Redirectionare in 3 secunde!']);
        }
    }

    public function getGrades($userid, $materie){
        $user = User::find($userid);
        $note = Grades::where('StudentID',$userid)->where('Subject',$materie)->orderBy('ID','desc')->get();
        return json_encode($note);
    }
}
