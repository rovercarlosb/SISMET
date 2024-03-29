$(document).on('ready',principal);

$('#disease').hide();

var factors= [];
var ids = [];
var names = [];
var globalFactorsIds = [];
var globalFactorsNames = [];
var globalFactorsDescriptions = [];
var $modalRecommendation;
var $modalDisease;

var respuesta =0;
function principal()
{
    $.ajax({
        url: './enfermedades/factores'
    }).done(function (data) {
        $.each(data,function(key,value)
        {
            globalFactorsIds.push(value.id);
            globalFactorsNames.push(value.name);
            globalFactorsDescriptions.push(value.descripcion);
        });
    });

    // SÍNTOMAS
    $('#sintomaAdd').on('click',sintomaAdd);

    // ANTECEDENTES
    $('#antecedenteAdd').on('click',antecedenteAdd);

    // OTROS FACTORES
    $('#otroAdd').on('click',otroAdd);

    $('body').on('click','[data-takeout]',takeout);

    $('#newDiagnostic').on('click',newDiagnostic);
    $('#forwardChaining').on('click',forwardChaining);

    $modalRecommendation = $('#modalRecommendation');
    $modalDisease = $('#modalDisease');

    $('#disease').on('click',modalDisease)


    $('#new_diases').on('change', addDisease)

    $('body').on('click','[data-recommendation]',modalRecommendation);
    $('#formDiagnostico').on('submit', save_diagnostic);
}

function sintomaAdd()
{
    var sintoma = $('#sintoma').val();
    if( sintoma.length == 0){
        showmessage('Seleccione síntoma',1);
        return;
    }

    $.ajax({
        url: $('#data-sim').attr('data-url')+'/'+sintoma,
        method: 'GET'
    }).done(function(data) {
        if( data.success == 'true' )
        {
            if( hasNotBeenAdded(data.data.id)  ){
                factors.push(data.data.id);
                var toAppend =
                    '<tr data-factorId="'+data.data.id+'">' +
                    '<td>'+data.data.name+'</td>'+
                    '<td><button data-takeout class="btn btn-danger">Quitar</button></td>'+
                    '</tr>';
                $('#factorList').append(toAppend);
            }
            else
                showmessage('El síntoma ya ha sido agregado a la lista.',1);
        }else
            showmessage(data.message,1);
    });
}

function antecedenteAdd()
{
    var antecedente = $('#antecedente').val();
    if( antecedente.length == 0){
        showmessage('Seleccione antecedente',1);
        return;
    }

    $.ajax({
        url: $('#data-ant').attr('data-url')+'/'+antecedente,
        method: 'GET'
    }).done(function(data) {
        if( data.success == 'true' )
        {
            if( hasNotBeenAdded(data.data.id)  ){
                factors.push(data.data.id);
                var toAppend =
                    '<tr data-factorId="'+data.data.id+'">' +
                    '<td>'+data.data.name+'</td>'+
                    '<td><button data-takeout class="btn btn-danger">Quitar</button></td>'+
                    '</tr>';
                $('#factorList').append(toAppend);
            }
            else
                showmessage('El antecedente ya ha sido agregado a la lista.',1);
        }else
            showmessage(data.message,1);
    });
}

function otroAdd()
{
    var otro = $('#otro').val();
    if( otro.length == 0){
        showmessage('Seleccione factor',1);
        return;
    }

    $.ajax({
        url: $('#data-otro').attr('data-url')+'/'+otro,
        method: 'GET'
    }).done(function(data) {
        if( data.success == 'true' )
        {
            if( hasNotBeenAdded(data.data.id)  ){
                factors.push(data.data.id);
                var toAppend =
                    '<tr data-factorId="'+data.data.id+'">' +
                    '<td>'+data.data.name+'</td>'+
                    '<td><button data-takeout class="btn btn-danger">Quitar</button></td>'+
                    '</tr>';
                $('#factorList').append(toAppend);
            }
            else
                showmessage('El factor ya ha sido agregado a la lista.',1);
        }else
            showmessage(data.message,1);
    });
}

function hasNotBeenAdded(factorId)
{
    for( var i=0;i<factors.length;i++)
        if( factors[i]== factorId )
            return false;
    return true;
}

function takeout()
{
    var factorId = $(this).parent().parent().attr('data-factorId');
    var tr = $(this).parent().parent();
    tr.remove();
    deleteElement(factors,factorId);
}

function deleteElement(  array, element ){
    var pos = 0;
    for( var i=0; i<array.length;i++ )
        if( array[i] == element )
            pos = i;

    array.splice(pos,1);
}

function showmessage( message, error )
{
    var icon = 'ti-thumb-up';
    var type = 'success';
    if( error==1 )
    {
        icon = 'ti-thumb-down';
        type = 'danger';
    }

    $.notify({
        icon: icon,
        message: '<b>'+message+'</b>'

    },{
        type: type,
        timer: 500
    });
}

function newDiagnostic()
{
    location.reload();
}

function forwardChaining()
{
    if( factors.length == 0 ) {
        showmessage('Debe seleccionar por lo menos un factor.', 1);
        return;
    }

    var timer = $('#forwardChaining').attr('data-timer');
    var data = JSON.stringify(factors);
    console.log(factors);
    console.log(data);
    var url = route + '/diagnostico/forwardChaining'
    axios.post(url, {factors:data,timer:timer}).then(response => {
        console.log(response);
        if(response.status == '401'){
            alert('La sesión se ha vencido, por favor recargue la pagina!.')
        }
        if(response.data.success == 'true' ){
          ids   = [];
          names = [];
            $.each(response.data.data,function(key,value){
                // Possible diseases
                ids.push(value.id);
                names.push(value.percentage+'%'+value.disease_name);

                diagnose();
            })

        }else showmessage(response.data.message, 1);
    }).catch(error => {
        console.log(error)
        if(error.response.status == '401'){
            alert('La sesión se ha vencido, por favor recargue la pagina!.')
        }
    });
}

// LOGIC EXPERT-DIAGNOSTIC

// Associative array length
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
let cont = 0;
function diagnose() {
    var diseaseFactors = [];
    for (var i=0; i<ids.length; ++i) {
        var disease_id = ids[i];
        var url_diag = route + '/enfermedades/factores/' + disease_id
        axios.get(url_diag).then(response => {
            console.log(response);
            if(response.status == '401'){
                alert('La sesión se ha vencido.')
            }
            var arreglo = [];
            $.each(response.data.factorIds,function(key,value)
            {
                arreglo.push(value.factor_id);
            });

            diseaseFactors[response.data.rule_id] = arreglo;
            // When all the factors were loaded
            if (Object.size(diseaseFactors) == ids.length) {
                startDiagnoseQuestions(diseaseFactors);
            }
        }).catch(error => {
            console.log(error)
            console.log(error.response)
            if(error.response.status == '401'){
                cont++;
            }
            if(cont == 1){
                alert('La sesión se ha vencido.')
                /*window.location.reload()*/
            }
        });
        /*$.ajax({
            url:'./enfermedades/factores/'+disease_id
        }).done(function (data) {
            var arreglo = [];
            $.each(data.factorIds,function(key,value)
            {
                arreglo.push(value.factor_id);
            });

            diseaseFactors[data.rule_id] = arreglo;
            // When all the factors were loaded
            if (Object.size(diseaseFactors) == ids.length) {
                startDiagnoseQuestions(diseaseFactors);
            }
        });*/
    }
}

function startDiagnoseQuestions(diseaseFactors) {
    $('#answer').html('');
    diagnoseDisease(0, diseaseFactors);
}

function diagnoseDisease(diagnose_position, diseaseFactors) {
    if (diagnose_position == ids.length) {
        // CUANDO NO DETECTA NADA
        var timer = $('#forwardChaining').attr('data-timer');

        $.ajax({
            url: '../diagnostico/timer',
            method: 'POST',
            data:{timer:timer},
            dataType:'json',
            headers : {
                'X-CSRF-TOKEN' : $('#_token').val()
            }
        }).done(function() { });
        return;
    }

    var disease_id = ids[diagnose_position];
    var name = names[diagnose_position];
    var symptoms = diseaseFactors[disease_id];

    swal.setDefaults({
        confirmButtonText: 'Sí, lo padezco',
        cancelButtonText: 'No',
        showCancelButton: true,
        animation: false
    });

    var steps = [];
    for (var i=0; i<symptoms.length; ++i) {
        if (notSelectedSymptom(symptoms[i])) {
            steps.push({
                title: 'Usted presenta este factor?',
                text: factorName(symptoms[i])
            });
        }
    }

    swal.queue(steps).then(function () {
        console.log(names);
        swal({
            title: 'Posibles diagnosticos: <br> * '+name+' <br> *'+names[names.length - 1] ,
            confirmButtonText: 'Entendido !',
            showCancelButton: false
        }).finally(function() {
            swal.resetDefaults();
            var timer = $('#forwardChaining').attr('data-timer');

            $.ajax({
                url: '../diagnostico/timer',
                method: 'POST',
                data:{timer:timer},
                dataType:'json',
                headers : {
                    'X-CSRF-TOKEN' : $('#_token').val()
                }
            }).done(function() {
                // respuesta = disease_id;
                $('#answer').append('<button class="btn btn-success" data-recommendation_name="'+name+'"  data-recommendation="'+disease_id+'">'+name+'</button><input type="radio" name="sele" value="'+disease_id+'" style="margin-left: 10px"> <br>');
                $('#answer').append('<button class="btn btn-success" data-recommendation_name="'+names[names.length - 1]+'"  data-recommendation="'+ids[ids.length - 1]+'">'+names[names.length - 1]+'</button><input type="radio" name="sele" value="'+ids[ids.length - 1]+'" style="margin-left: 10px"> <br>');
                $('#disease').show();

            });
            return;
            //diagnoseDisease(diagnose_position+1, diseaseFactors);
        });
    }, function () {
        swal({
            title: 'Se ha descartado la enfermedad: '+name,
            confirmButtonText: 'Entendido !',
            showCancelButton: false
        }).finally(function() {
            swal.resetDefaults();
            diagnoseDisease(diagnose_position+1, diseaseFactors);
        });
    })
}

function factorName(factorId) {
    for (var i=0; i<globalFactorsIds.length; ++i) {
        if (globalFactorsIds[i] == factorId)
            return globalFactorsNames[i].bold()+'\n ('+globalFactorsDescriptions[i]+' )';
    }
}

function notSelectedSymptom(symptom_id) {
    for (var i=0; i<factors.length; ++i)
        if (factors[i] == symptom_id)
            return false;
    return true;
}

function modalRecommendation()
{
    var recommendation = $(this).data('recommendation');
    var recommendation_name = $(this).data('recommendation_name');
    $('#name_recommendation').val(recommendation_name);
    $('#recommendations').html('');

    $.ajax({
        url:'./enfermedades/recomendaciones/'+recommendation
    }).done( function (data) {
        $.each(data,function(key,value)
        {
            $('#recommendations').append('<i class="fa fa-check"></i> '+value.description+'<br>');
        });
    });

    $modalRecommendation.modal('show');
}

function modalDisease()
{
    $modalDisease.modal('show');
}

    $("input[name='sele']").change(function () {   
            alert($(this).val());
    });

function addDisease(){

    let id = $('#new_diases option:selected').val();
    let name = $('#new_diases option:selected').text();
    $('#answer').append('<button class="btn btn-success" data-recommendation_name="'+name+'"  data-recommendation="'+id+'">'+name+'</button><input type="radio" name="sele" value="'+id+'" style="margin-left: 10px">');
    $('#cerrar').click();

}

function save_diagnostic()
{    
     event.preventDefault();

     respuesta = $("input[name='sele']:checked").val();

     if( respuesta == 0 )
     {
         showmessage('El diagnóstico no se ha generado',1);
         return;
     }

     var patient = $('#patienId').val();

     $.ajax({
         url: $('#formDiagnostico').attr('action')+'/'+patient+'/'+respuesta,
         data: new FormData(this),
         dataType: "JSON",
         processData: false,
         contentType: false,
         method: 'POST'
     
     }).done( function (data) {
         if( data.success == 'true' ){

             showmessage(data.message,0);
             setTimeout(function(){
                    location.reload();
                }, 2000);
         }

         else{
             showmessage(data.message,1);
         }
     });

     // './diagnostico/guardar/'+patient+'/'+respuesta,
}