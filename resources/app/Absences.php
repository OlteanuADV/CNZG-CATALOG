<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absences extends Model
{
    public $table = 'absences';
    protected $primaryKey = 'ID';
    protected $fillable = ['StudentID', 'AbsenceDate', 'Subject', 'PostedBy', 'Motivated'];
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User', 'StudentID', 'ID');
    }

    public function posted(){
        return $this->belongsTo('App\User', 'PostedBy', 'ID');
    }
}
