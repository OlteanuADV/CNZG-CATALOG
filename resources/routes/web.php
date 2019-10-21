<?php
/*
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
Route::prefix('/classes')->group(function(){
    Route::get('/all','Pages@showAllClasses')->middleware('auth');
    Route::get('/mine','Pages@mineClasses')->middleware('auth');
    Route::get('/{id}', 'Pages@myClass')->middleware('auth');
});
*/



Route::prefix('/api')->group(function(){
    Route::post('/login', 'Requests@postLogin')->middleware('guest');
    Route::get('/fetchUser', 'Requests@fetchUser');
    Route::get('/fetchInbox', 'Requests@fetchInbox');
    Route::get('/fetchMyClass/{id}', 'Requests@fetchMyClass');
    Route::get('/fetchIndex', 'Requests@fetchIndex');
    Route::get('/getGrades/{userid}/{materie}','Requests@getGrades')->middleware('auth');
    Route::post('/postNewGrade','Requests@postNewGrade')->middleware('auth');
    Route::post('/postNewAbs', 'Requests@postNewAbs')->middleware('auth');
    Route::post('/choseMyChief', 'Requests@choseMyChief')->middleware('auth');
    Route::post('/buzzMyClass', 'Requests@buzzMyClass')->middleware('auth');
    Route::post('/motivateAbsence', 'Requests@motivateAbsence')->middleware('auth');
    Route::post('/demotivateAbsence', 'Requests@demotivateAbsence')->middleware('auth');
    Route::post('/addNewStudent', 'Requests@addNewStudent')->middleware('auth');
});

Route::get('/{any}','Pages@spaControll')->where('any', '^(?!api).*$');