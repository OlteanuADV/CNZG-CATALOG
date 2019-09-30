<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    public $table = 'subjects';
    protected $primaryKey = 'ID';
    protected $fillable = ['Number', 'Character'];
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User', 'ID', 'ID');
    }
}
