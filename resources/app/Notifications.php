<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\General;
use Auth, DB;
class Notifications extends Model
{
    public $table = 'notifications';
    protected $primaryKey = 'ID';
    protected $fillable = ['PostedBy', 'PostedOn','UserID','Message', 'Read'];
    public $timestamps = false;

    public static function addNotif($user, $text){
        $text = General::olt_purify($text);
        $save = new Notifications;
        $save->PostedBy = Auth::user()->ID;
        $save->PostedOn = date('Y-m-d H:i:s');
        $save->UserID = $user;
        $save->Message = $text;
        $save->Read = 0;
        $save->save();
    }

    public function user() {
        return $this->belongsTo('App\User', 'ID', 'UserID');
    }

    public function posted(){
        return $this->belongsTo('App\User', 'ID', 'PostedBy');
    }
}
