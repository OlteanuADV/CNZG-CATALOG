<?php

Route::get('/','Pages@showIndex');
Route::get('/home','Pages@showIndex');
Route::get('/login','Pages@showLogin')->middleware('guest')->name('login');
Route::post('/login', 'Requests@postLogin')->middleware('guest');

Route::get('/profile/{id}','Pages@showProfile')->middleware('auth');

Route::get('/logout',function(){
    Auth::logout();
    return redirect('/')->with('success','Deconectare cu succes.');
})->middleware('auth');

Route::prefix('/api')->group(function(){
    Route::get('/getGrades/{userid}/{materie}','Requests@getGrades')->middleware('auth');
});

Route::prefix('/classes')->group(function(){
    Route::get('/my', 'Pages@myClass')->middleware('auth');
});