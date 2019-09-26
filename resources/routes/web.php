<?php

Route::get('/','Pages@showIndex');
Route::get('/home','Pages@showIndex');
Route::get('/login','Pages@showLogin')->middleware('guest')->name('login');
Route::post('/login', 'Requests@postLogin')->middleware('guest');

Route::get('/profile/{id}','Pages@showProfile')->middleware('auth');

Route::get('/logout',function(){
    Auth::logout();
})->middleware('auth');

Route::prefix('/api')->group(function(){
    Route::get('/getGrades/{userid}/{materie}','Requests@getGrades')->middleware('auth');
});