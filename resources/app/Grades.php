<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    public $table = 'grades';
    protected $primaryKey = 'ID';
    protected $fillable = ['StudentID', 'Value','Date','PostedBy'];
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User', 'ID', 'StudentID');
    }

    public function posted(){
        return $this->belongsTo('App\User', 'ID', 'PostedBy');
    }
}
