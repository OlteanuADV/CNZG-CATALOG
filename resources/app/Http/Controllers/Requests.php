<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, DB, App\User, Cache, Session, Carbon, App\General, App\Subjects, App\Absences, App\Grades, App\Notifications, App\Classes;

class Requests extends Controller
{
    public function fetchProfile($id){
        Cache::forget('showProfile_'.$id);
        $user                               = User::find($id);
        if(!$user)
            return redirect('/')->with('danger','This user doesn`t exists!');
        $data = Cache::remember('showProfile_'.$id, 1800, function() use ($user){
            if($user->InSchoolFunction == 1)
                $user->prof_subject     = Subjects::find($user->Subject)->Name;
            if($user->Class !== 0)
            {
                $data['user']               = $user;
                $data['class']              = $user->class()->first();
                $data['diriginte']          = User::where('Class',$user->Class)->where('InSchoolFunction', '1')->first();
                $data['profesori']          = Classes::getTeachers($user->Class);
                $data['materii']            = Classes::getSubjects($user->Class);
                $data['absente']            = $user->absences()->orderBy('AbsenceDate','desc')->get();
            }
            else
            {
                $data['user']               = $user;
                $data['class']              = 0;
                $data['diriginte']          = 0;
                $data['profesori']          = 0;
                $data['materii']            = 0;
                $data['absente']            = 0;
            }
            return (object) $data;
        });
        return json_encode($data);
    }

    public function fetchMineClasses(){
        if(Auth::user()->InSchoolFunction == 0)
            return redirect('/')->with('danger','Din pacate nu ai acces la aceasta pagina!');
        
        $claseles            = Cache::remember('mineclasses_'.Auth::user()->ID, 3600, function() {
            $classes            = Classes::all();
            $myclass            = [];

            foreach($classes as $class){
                $asd        = Classes::getTeachers($class->ID);
                foreach($asd as $teach)
                {
                    if(Auth::user() == $teach)
                    {
                        $class->users        = $class->users()->where('InSchoolFunction', 0)->get();
                        $class->diriginte    = $class->users()->where('InSchoolFunction', 1)->first();
                        array_push($myclass, $class);
                    }
                }
            }
            return $myclass;
        });
        return $claseles;
    }

    public function fetchAllClasses(){
        $classes = Cache::remember('showAllClasses', 3600, function(){
            $data = Classes::all();
            foreach($data as $clas){
                $clas->diriginte = $clas->users()->where('InSchoolFunction', 1)->first();
                $clas->users = $clas->users()->where('InSchoolFunction', 0)->get();
            }
            return $data;
        });
        return json_encode($classes);
    }

    public function fetchMyClass($id){
        $class = Classes::find($id);
        $class->chief = $class->chief();
        if(!$class)
            return redirect('/')->with('danger','Aceasta clasa nu exista!');
        
        $data = Cache::remember('myclass_'.$id, 1800, function()  use ($class) {
            $data['students']           = $class->users()->where('InSchoolFunction',0)->orderBy('LastName','ASC')->get();
            $data['diriginte']          = $class->users()->where('InSchoolFunction',1)->first();
            $data['profesori']          = Classes::getTeachers($class->ID);
            $data['materii']            = Classes::getSubjects($class->ID);
            return (object) $data;
        });
        return json_encode(['students' => $data->students,'class'=> $class, 'diriginte' => $data->diriginte, 'profesori' => $data->profesori, 'materii' => $data->materii]);
    }

    public function fetchInbox() {
        $notifications = Auth::user()->notifications()->orderBy('ID','desc')->paginate(10);
        Notifications::where('UserID', Auth::user()->ID)->where('Read', 0)->update([
            'Read'=>1
        ]);
        return json_encode($notifications);
    }

    public function fetchIndex(){
        $data = Cache::remember('showIndex', 1800, function(){
            $data['total_students']         = User::where('InSchoolFunction','0')->count();
            $data['total_teachers']         = User::where('InSchoolFunction','>','0')->count();
            $data['total_classes']          = Classes::count();
            $data['school']                 = General::getSchoolInfo();
            return (object) $data;
        });
        return json_encode($data);
    }

    public function fetchUser(){
        if(!Auth::check())
            return json_encode(['Auth' => ['check' => false]]);
        else{
            return json_encode(['Auth' => ['checked' => true, 'user' => Auth::user()]]);
        }
    }
//post fgunctions
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
            Cache::forget('showProfile_'.$user->ID);
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
            Cache::forget('showProfile_'.$user->ID);
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


    public function motivateAbsence(Request $req) {
        $req->validate([
            'id' => 'required'
        ]);
        
        $id = $req->input('id');
        $absenta = Absences::find($id);
        if(Auth::user()->InSchoolFunction == 0)
            return json_encode(['success' => 0,'message'=>'Nu poti face acest lucru!']);
        if(!$absenta)
            return json_encode(['success' => 0,'message'=>'Absenta nu exista.']);

        if(Auth::user()->InSchoolFunction > 1 || (Auth::user()->InSchoolFunction > 0 && Auth::user()->Class == $absenta->user->Class)){
            $absenta->Motivated = 1;
            $absenta->save();
            Notifications::addNotif($absenta->user->ID, 'Dirigintele '.Auth::user()->LastName.' '.Auth::user()->FirstName.' ti-a motivat o absenta in data de '.$absenta->AbsenceDate.'!');
            Cache::forget('showProfile_'.$absenta->user->ID);
            return json_encode(['success' => 1,'message'=> 'Absenta motivata cu succes!']);
        }
    }

    public function demotivateAbsence(Request $req) {
        $req->validate([
            'id' => 'required'
        ]);
        
        $id = $req->input('id');
        $absenta = Absences::find($id);
        if(Auth::user()->InSchoolFunction == 0)
            return json_encode(['success' => 0,'message'=>'Nu poti face acest lucru!']);
        if(!$absenta)
            return json_encode(['success' => 0,'message'=>'Absenta nu exista.']);

        if(Auth::user()->InSchoolFunction > 1 || (Auth::user()->InSchoolFunction > 0 && Auth::user()->Class == $absenta->user->Class)) {
            $absenta->Motivated = 0;
            $absenta->save();
            Notifications::addNotif($absenta->user->ID, 'Dirigintele '.Auth::user()->LastName.' '.Auth::user()->FirstName.' ti-a demotivat o absenta in data de '.$absenta->AbsenceDate.'!');
            Cache::forget('showProfile_'.$absenta->user->ID);
            return json_encode(['success' => 1,'message'=> 'Absenta demotivata cu succes!']);
        }
    }

    public function addNewStudent(Request $req){
        $req->validate([
            'newLastN' => 'required',
            'newFirstN' => 'required',
            'newEmail' => 'required'
        ]);
        if(Auth::user()->InSchoolFunction == 0)
            return json_encode(['success' => 0,'message'=>'Nu poti face acest lucru!']);
        $LastN = $req->input('newLastN');
        $FirstN = $req->input('newFirstN');
        $Email = $req->input('newEmail');

        $user = new User;
        $user->LastName = General::olt_purify($LastN);
        $user->FirstName = General::olt_purify($FirstN);
        $user->Email = General::olt_purify($Email);
        $string = General::randString(8);
        $user->Password = $string;
        $user->InSchoolFunction = 0;
        $user->Class = Auth::user()->Class;
        $user->Subject = 0;
        $user->save();
        Cache::forget('myclass_'.Auth::user()->Class);
        return json_encode(['success' => 1,'message'=> 'Elev adaugat!Parola acestuia este '.$string.'!']);
    }

}
