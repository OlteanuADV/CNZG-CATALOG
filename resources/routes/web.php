<?php

Route::get('/','Pages@showIndex');
Route::get('/home','Pages@showIndex');
Route::get('/login','Pages@showLogin')->middleware('guest')->name('login');
Route::post('/login', 'Requests@postLogin')->middleware('guest');
Route::get('/inbox', 'Pages@showInbox')->middleware('auth');


Route::get('/profile/{id}','Pages@showProfile')->middleware('auth');

Route::get('/logout',function(){
    Auth::logout();
    return redirect('/')->with('success','Deconectare cu succes.');
})->middleware('auth');

Route::prefix('/api')->group(function(){
    Route::get('/getGrades/{userid}/{materie}','Requests@getGrades')->middleware('auth');
    Route::post('/postNewGrade','Requests@postNewGrade')->middleware('auth');
    Route::post('/postNewAbs', 'Requests@postNewAbs')->middleware('auth');
});

Route::prefix('/classes')->group(function(){
    Route::get('/all','Pages@showAllClasses')->middleware('auth');
    Route::get('/mine','Pages@mineClasses')->middleware('auth');
    Route::get('/{id}', 'Pages@myClass')->middleware('auth');
});
