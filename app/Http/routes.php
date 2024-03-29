<?php

Route::auth();
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/validate/auth', 'Auth\AuthController@validate_session');
// Patient routes
    Route::get('/pacientes', 'PatientController@index')->name('pacientes');
    Route::post('/pacientes/registrar', 'PatientController@store');
    Route::post('/pacientes/modificar', 'PatientController@edit');
    Route::post('/pacientes/eliminar', 'PatientController@delete');
    Route::post('/pacientes/desactivar', 'PatientController@deactivate');
    Route::post('/pacientes/activar', 'PatientController@activate');
    Route::get('/paciente/perfil/{id}', 'PatientController@profile')->name('profile');

//Appoinment routes
    Route::get('/citas', 'AppoinmentController@index');
    Route::post('/citas/registrar', 'AppoinmentController@store');
    Route::post('/citas/modificar', 'AppoinmentController@update');
    Route::get('/citas/eliminar/{id}', 'AppoinmentController@destroy')->name('citas.eliminar');

// Symptom routes
    Route::get('factores', 'SymptomController@index');
    Route::post('/symptom/registrar', 'SymptomController@postSymptom');
    Route::post('/symptom/modificar', 'SymptomController@putSymptom');
    Route::post('/symptom/eliminar', 'SymptomController@deleteSymptom');

    Route::get('factores/{factorName}', 'DiagnosisController@factorNombresId');

// General Factor routes
    Route::get('/factor/nombre/{sintoma}', 'SymptomController@getSymptom');

// Traer diagnostico y cita de paciente
    Route::get('diagnosis/patient/{id}', 'PatientController@getDiagnosis');
    Route::get('appointment/patient/{id}', 'AppoinmentController@getAppointment');


    Route::get('reporte/diagnostico/{id}', 'PatientController@reportePDF')->name('diagnostico.pdf');


// Antecedente routes
    Route::post('/antecedente/registrar', 'FactorController@postAntecedent');
    Route::post('/antecedente/modificar', 'FactorController@putAntecedent');
    Route::post('/antecedente/eliminar', 'FactorController@deleteAntecedent');

// Antecedente routes
    Route::post('/factor/registrar', 'OtherController@postFactor');
    Route::post('/factor/modificar', 'OtherController@putFactor');
    Route::post('/factor/eliminar', 'OtherController@deleteFactor');

// Medication routes
    Route::get('/recomendaciones', 'MedicationController@index');
    Route::post('/medicamentos/registrar', 'MedicationController@store');
    Route::post('/medicamentos/modificar', 'MedicationController@edit');
    Route::post('/medicamentos/eliminar', 'MedicationController@delete');

    Route::get('/recomendaciones/nombres', 'MedicationController@recomendationNames');
    Route::get('/recomendaciones/{name}', 'MedicationController@recomendationName');

// Disease routes
    Route::get('enfermedades', 'DiseaseController@index');
    Route::post('enfermedad/registrar', 'DiseaseController@store');
    Route::post('enfermedad/modificar', 'DiseaseController@edit');
    Route::get('enfermedad/eliminar/{id}', 'DiseaseController@delete');

// Diagnostic routes
    Route::get('diagnostico-{patientId}','DiagnosisController@index');
    Route::get('diagnostico/all','DiagnosisController@getAll');
    Route::get('diagnostico/enfermedades','DiagnosisController@diseases');
    Route::post('diagnostico/timer','DiagnosisController@writeTimer');
    Route::get('enfermedades/medicamentos/{disease_id}','DiagnosisController@medication');
    Route::get('enfermedades/{id}/sintomas','DiagnosisController@symptomsByDisease');
    Route::get('enfermedades/factores/{ruleId}','DiagnosisController@diseaseFactors');
    Route::get('enfermedades/factores','DiagnosisController@allFactors');
    Route::get('enfermedades/recomendaciones/{ruleId}','DiagnosisController@diseaseRecommendations');

    // Guardar diagnóstico
    Route::post('diagnostico/guardar/{patientId}/{ruleId}','DiagnosisController@saveDiagnostic');

    Route::post('diagnostico/forwardChaining','DiagnosisController@forwardChaining');

// Knowledge-base routes
    Route::get('conocimiento','KnowledgeController@index');
    Route::get('asignar/sintomas/{id}','KnowledgeController@getAssign');
    Route::get('asignar/sintoma/{disease}/{symptom}','KnowledgeController@getAssignSymptom');
    Route::get('desasignar/sintoma/{disease}/{symptom}','KnowledgeController@getNotAssignSymptom');

    Route::get('asignar/reglas/{disease}','KnowledgeController@getAssignRule');

    Route::get('asignar/medicamentos/{id}','KnowledgeController@getAssignMed');
    Route::get('asignar/medicamento/{disease}/{medication}','KnowledgeController@getAssignMedication');
    Route::get('desasignar/medicamento/{disease}/{medication}','KnowledgeController@getNotAssignMedication');

    Route::get('nueva-regla','KnowledgeController@newRule');

    Route::post('guardar/regla','KnowledgeController@postNewRule');
    Route::post('eliminar/regla','KnowledgeController@postDeleteRule');
    Route::get('rules/enfermedad/{disease}','KnowledgeController@getRules');
    Route::get('recommendations/rule/{rule}','KnowledgeController@getRecommendations');
    Route::get('factors/rule/{rule}','KnowledgeController@getFactors');

    // Ayuda en línea
    Route::get('ayuda','HomeController@helpExpert');

    //Envio de correos
    Route::get('historial/mail/{id}','PatientController@historialMail');
    Route::get('appointment/mail/{id}','AppoinmentController@appointmentMail');
    Route::get('citas/mail','PatientController@citasMail');

});
