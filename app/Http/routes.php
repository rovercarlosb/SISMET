<?php

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

// Patient routes
Route::group(['middleware' => 'auth'], function () {

    Route::get('/pacientes', 'PatientController@index');
    Route::post('/pacientes/registrar', 'PatientController@store');
    Route::post('/pacientes/modificar', 'PatientController@edit');
    Route::post('/pacientes/eliminar', 'PatientController@delete');

// Symptom routes

// Medication routes
    Route::get('/medicamentos', 'MedicationController@index');
    Route::post('/medicamentos/registrar', 'MedicationController@store');
    Route::post('/medicamentos/modificar', 'MedicationController@edit');
    Route::post('/medicamentos/eliminar', 'MedicationController@delete');

// Disease routes

// Diagnostic routes
});
