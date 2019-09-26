<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    public $table = 'grades';
    protected $primaryKey = 'ID';
    protected $fillable = ['StudentID', 'Value','Date','PostedBy'];
    public $timestamps = false;
}
