<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, DB, App\User, Cache, Session, Carbon, App\General, App\Subjects, App\Absences, App\Grades, App\Notifications;

class Requests extends Controller
{
    public function postLogin(Request $r){
        $r->validate([
            'password' => 'required',
            'lastname' => 'required',
            'firstname' => 'required'
        ]);
        $p                      = $r->input('password');
        $l                      = $r->input('lastname');
        $f                      = $r->input('firstname');

        $user                   = User::where('LastName', $l)->where('FirstName',$f)->where('Password',$p)->first();
        if(!$user)
            return json_encode(['message'=>'Utilizatorul nu a fost gasit!']);
        else
        {
            Auth::loginUsingId($user->ID);
            return json_encode(['success' => 1,'message'=>'Conectare cu succes!Redirectionare in 3 secunde!']);
        }
    }

    public function getGrades($userid, $materie) {
        $user                   = User::find($userid);
        $note                   = $user->grades()->where('Subject',$materie)->orderBy('ID','desc')->get();
        return json_encode($note);
    }

    public function postNewGrade(Request $req){
        $req->validate([
            'materie' => 'required',
            'nota' => 'required',
            'data' => 'required',
            'user_id' => 'required'
        ]);

        $materie                = $req->input('materie');
        $nota                   = $req->input('nota');
        $data                   = $req->input('data');
        $user_id                = $req->input('user_id');

        if(strtotime($data) > strtotime(date('Y-m-d')))
            return json_encode(['success' => 0,'message'=>'Nu poti pune note in viitor!']);

        $user = User::find($user_id);
        if(!$user)
            return json_encode(['success' => 0,'message'=>'Acest cont de utilizator nu exista!']);

        if($nota > 10)
            return json_encode(['success' => 0,'message'=>'Nota maxima este 10!']);

        if($nota < 1) 
            return json_encode(['success' => 0,'message'=>'Nota minima este 1!']);
        if(Auth::user()->InSchoolFunction > 0 && ((Auth::user()->InSchoolFunction == 1 && Auth::user()->subject->ID == $materie) || Auth::user()->InSchoolFunction > 1 || (Auth::user()->InSchoolFunction == 1 && Auth::user()->Class == $user->Class))) {
            $newnot = new Grades;
            $newnot->StudentID = $user->ID;
            $newnot->Subject = $materie;
            $newnot->Value = $nota;
            $newnot->Date = $data;
            $newnot->PostedBy = Auth::user()->ID;
            $newnot->save();

            $numemat = Subjects::find($materie)->Nume;
            Notifications::addNotif($user->ID, 'Profesorul '.Auth::user()->LastName.' '.Auth::user()->FirstName.' ti-a adaugat nota '.$nota.' la materia '.$numemat.'!');
            return json_encode(['success' => 1,'message'=>'I-ati adaugat elevului nota '.$nota.' la materia '.$numemat.'!']);
        }
        else
            return json_encode(['success' => 0,'message'=>'Nu poti face acest lucru!']);
    }

    public function postNewAbs(Request $req){
        $req->validate([
            'materie' => 'required',
            'data' => 'required',
            'user_id' => 'required'
        ]);

        $materie                = $req->input('materie');
        $data                   = $req->input('data');
        $user_id                = $req->input('user_id');
        
        if(strtotime($data) > strtotime(date('Y-m-d')))
            return json_encode(['success' => 0,'message'=>'Nu poti pune absente in viitor!']);
            
        $user = User::find($user_id);
        if(!$user)
            return json_encode(['success' => 0,'message'=>'Acest cont de utilizator nu exista!']);

        if(Auth::user()->InSchoolFunction > 0 && ((Auth::user()->InSchoolFunction == 1 && Auth::user()->subject->ID == $materie) || Auth::user()->InSchoolFunction > 1 || (Auth::user()->InSchoolFunction == 1 && Auth::user()->Class == $user->Class))) {
            $newabs = new Absences;
            $newabs->StudentID = $user->ID;
            $newabs->AbsenceDate = $data;
            $newabs->Subject = $materie;
            $newabs->PostedBy = Auth::user()->ID;
            $newabs->Motivated = 0;
            $newabs->save(); 

            $numemat = Subjects::find($materie)->Nume;
            Notifications::addNotif($user->ID, 'Profesorul '.Auth::user()->LastName.' '.Auth::user()->FirstName.' ti-a adaugat o absenta nemotivata in data de '.$data.' la materia '.$numemat.'!');
            return json_encode(['success' => 1,'message'=> 'I-ati adaugat elevului o absenta nemotivata in data de  '.$data.' la materia '.$numemat.'!']);
        } else
        return json_encode(['success' => 0,'message'=>'Nu poti face acest lucru!']);
        
    }

    public function choseMyChief(Request $req){
        $req->validate([
            'sefulclasei' => 'required'
        ]);

        $chief = $req->input('sefulclasei');
        
        if(Auth::user()->InSchoolFunction == 0)
            return json_encode(['success' => 0,'message'=>'Nu poti face acest lucru!']);
        
        $actual_chief = Auth::user()->class()->first()->Chief;

        if($chief == $actual_chief)
            return json_encode(['success' => 0,'message'=> 'Nu poti alege aceelasi sef!']);
        
        $chief_user = User::find($chief);
        if(Auth::user()->Class !== $chief_user->Class)
            return json_encode(['success' => 0,'message'=> 'Nu poti alege un sef care nu este in clasa dumneavoastra.!']);

        $clas = Auth::user()->class()->first();
        $clas->Chief = $chief;
        $clas->save();
        Notifications::addNotif($chief_user->ID, 'Dirigintele '.Auth::user()->LastName.' '.Auth::user()->FirstName.' tocmai te-a numit noul sef al clasei din care faci parte!');
        return json_encode(['success' => 1,'message'=> 'L-ati numit pe '.$chief_user->LastName.' '.$chief_user->FirstName.' noul sef al clasei!']);
    }
    public function buzzMyClass(Request $req){
        $req->validate([
            'mesajclasa' => 'required'
        ]);

        $msj = $req->input('mesajclasa');
        if(Auth::user()->InSchoolFunction == 0)
            return json_encode(['success' => 0,'message'=>'Nu poti face acest lucru!']);
        
        $class_users = User::where('Class',Auth::user()->Class)->where('InSchoolFunction', 0)->get();
        $text = General::olt_purify($msj);
        foreach($class_users as $us) {
            Notifications::addNotif($us->ID, '"'.$text.'", trimis de catre dirigintele '.Auth::user()->LastName.' '.Auth::user()->FirstName.'!');
        }
        return json_encode(['success' => 1,'message'=> 'Mesajul a fost trimis tuturor elevilor din clasa!']);
    }

}
