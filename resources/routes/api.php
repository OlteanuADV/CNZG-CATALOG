<?php

use Illuminate\Http\Request;
 
Route::post('/addNewStudent', 'Requests@addNewStudent')->middleware('auth');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
