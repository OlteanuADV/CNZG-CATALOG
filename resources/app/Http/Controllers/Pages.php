<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, DB, App\User, Cache, Session, Carbon, App\General, App\Classes, App\Absences, App\Notifications;


class Pages extends Controller
{
    public function showIndex(){
        $data = Cache::remember('showIndex', 1800, function(){
            $data['$total_students']         = User::where('InSchoolFunction','0')->count();
            $data['$total_teachers']         = User::where('InSchoolFunction','>','0')->count();
            $data['$total_classes']          = Classes::count();
            $data['$school']                 = General::getSchoolInfo();
            return $data;
        });
        return view('pages.index')
        ->with('total_students',        $data['$total_students'])
        ->with('total_teachers',        $data['$total_teachers'])
        ->with('school',                $data['$school'])
        ->with('total_classes',         $data['$total_classes']);
    }

    public function showLogin(){
        return view('pages.login');
    }

    public function showProfile($id){
        $user                               = User::find($id);
        if(!$user)
            return redirect('/')->with('danger','This user doesn`t exists!');
        $data = Cache::remember('showProfile_'.$id, 1800, function() use ($user){
            if($user->Class !== 0)
            {
                $data['$class']              = $user->class()->first();
                $data['$diriginte']          = User::where('Class',$user->Class)->where('InSchoolFunction', '1')->first();
                $data['$profesori']          = Classes::getTeachers($user->Class);
                $data['$materii']            = Classes::getSubjects($user->Class);
                $data['$absente']            = $user->absences()->orderBy('AbsenceDate','desc')->get();
            }
            else
            {
                $data['$class']              = 0;
                $data['$diriginte']          = 0;
                $data['$profesori']          = 0;
                $data['$materii']            = 0;
                $data['$absente']            = 0;
            }
            return $data;
        });
        return view('pages.profile')
        ->with('user',                  $user)
        ->with('class',                 $data['$class'])
        ->with('diriginte',             $data['$diriginte'])
        ->with('profesori',             $data['$profesori'])
        ->with('materii',               $data['$materii'])
        ->with('absente',               $data['$absente']);
    }

    public function myClass($id){
        $class = Classes::find($id);
        if(!$class)
            return redirect('/')->with('danger','Aceasta clasa nu exista!');
        
        $data = Cache::remember('myclass_'.$id, 1800, function()  use ($class) {
            $data['students']           = $class->users()->where('InSchoolFunction',0)->orderBy('LastName','ASC')->get();
            $data['diriginte']          = $class->users()->where('InSchoolFunction',1)->first();
            $data['profesori']          = Classes::getTeachers($class->ID);
            $data['materii']            = Classes::getSubjects($class->ID);
            return $data;
        });
        
        return view('pages.myclass')
        ->with('students',              $data['students'])
        ->with('class',                 $class)
        ->with('diriginte',             $data['diriginte'])
        ->with('profesori',             $data['profesori'])
        ->with('materii',               $data['materii']);
    }

    public function mineClasses(){
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

        return view('pages.mineclasses')->with('classes', $claseles);
    }

    public function showInbox(){
        $notifications = Auth::user()->notifications()->orderBy('ID','desc')->paginate(10);
        Notifications::where('UserID', Auth::user()->ID)->where('Read', 0)->update([
            'Read'=>1
        ]);
        return view('pages.inbox', compact('notifications'));
    }

    public function showAllClasses(){
        $data = Cache::remember('showAllClasses', 3600, function(){
            $data = Classes::all();
            foreach($data as $clas){
                $clas->diriginte = $class->users()->where('InSchoolFunction', 1)->first();
                $clas->users = $class->users()->where('InSchoolFunction', 0)->get();
            }
            return $data;
        });
    }
}
