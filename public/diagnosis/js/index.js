$(document).on('ready', principal);

var disease_simptom = [];

// UI selectors
var $sintomas;
var $buscador;
var $paginacion;

// Modal selectors
var $modalDetalles;
var $modalTratamiento;

function principal(){
    $sintomas = $('#noAsignados');
    $buscador = $('#search');
    $paginacion = $('.pagination');
    $buscador.on('input', buscarSintoma);

    // SoleS
    $modalDetalles    = $('#modalDetalles');
    $modalTratamiento = $('#modalTratamiento');

    $.getJSON('diagnostico/all', function (data) {
        full_data = data;
        // All symptoms are not-assigned, initially
        for(var i=0; i<full_data.length; i++)
        {
            full_data[i].noasignado = true;
        }
        loadSintomasSource(full_data);
        mostrarPagina(1);
        setNextAndBackPageEvents()
    });
}

var full_data;
var values = [];
var origen = [];
var destino = [];
var html = [];

// Arrays of possible diseases
var id = [];
var name_array = [];

function setNextAndBackPageEvents() {
    $('#next').on('click', function nextPage() {
        var pagina = $(this).parent().parent().find('li.active').attr("id");
        var ultima = $(this).parent().prev().attr("id");
        if (pagina<ultima)
            mostrarPagina(parseInt(pagina)+1);
    });
    $('#back').on('click', function nextPage() {
        var pagina = $(this).parent().parent().find('li.active').attr("id");
        if (pagina>1)
            mostrarPagina(parseInt(pagina)-1);
    });
}

function loadSintomasSource(data){
    var pag = 0;
    var j = 0;
    html[0] = '';
    for (var i=0; i<data.length; ++i) {
        if (data[i].noasignado) {
            if (j != 0 && j % 6 == 0) {
                pag++;
                html[pag] = '';
            }
            html[pag] += '<div data-detalle="' + data[i].id + '" class="sintoma col-md-4 text-center"><img  class="img-thumbnail img-rounded" src="./symptoms/images/' + data[i].imagen + '" style="height: 100px" onclick="showDescription(\'' + data[i].descripcion + '\')"/><label class="checkbox" style="font-size : 10px;"><input type="checkbox" data-toggle="checkbox" name="origen" value="' + data[i].id + '" />' + data[i].name + '</label></div>';
            j++;
        }
    }

    var pgntr = '<li id="backentorn"><a id="back">«</a></li>';
    // console.log(pag);
    for (var k = 0; k <= pag; k++) {
        pgntr += '<li id="' + (k + 1) + '"><a href="#" onclick="mostrarPagina(\'' + (k + 1) + '\')">' + (k + 1) + '</a></li>';
    }
    pgntr += '<li id="nextentorn"><a id="next" rel="next">»</a></li>';
    // console.log(pgntr);
    $paginacion.append(pgntr);
}

function mostrarPagina(pagina) {
    // Replace the symptoms displayed
    $sintomas.children().remove();
    $sintomas.append(html[pagina-1]);

    // Set the activate state to the new selected page-option
    var $ulPagination = $('ul.pagination');
    $ulPagination.find('li.active').removeClass('active');
    $('#'+pagina).addClass('active');

    // Enable and disable next and back buttons for first and last page-options
    if (pagina == 1)
        $('#backentorn').addClass('disabled');
    else
        $('#backentorn').attr('class','enabled');
    if ("nextentorn"  ==  $ulPagination.find('li.active').next().attr("id") )
        $('#nextentorn').addClass('disabled');
    else
        $('#nextentorn').attr('class','enabled');
}

function buscarSintoma() {
    var str = $(this).val();
    $sintomas.children().remove();

    // Empty query?
    if (str.length==0) {
        $paginacion.children().remove();
        loadSintomasSource(full_data);
        mostrarPagina(1);
        return;
    }

    // Filter symptoms by name
    var temporal = [];
    for (var i=0; i<full_data.length; i++) {
        var name = full_data[i].name;
        if (name.indexOf(str) > -1)
            temporal.push(full_data[i]);
    }
    $paginacion.children().remove();
    loadSintomasSource(temporal);
    mostrarPagina(1);
    setNextAndBackPageEvents();
}

function asignar() {
    $("input[name=origen]:checked").each(function(){
        values.push($(this));
    });
    $(values).each( function(i,element) {
        var _this = $(this);
        _this.attr('name','destino');
        _this.parent().parent().appendTo('#asignados');
        full_data[_this.val()-1].noasignado = false;
    });
    values.length=0;
    //actualizamos
    $paginacion.children().remove();
    loadSintomasSource(full_data);

    // SoleS Logic
    show_diseases();
}

function devolver() {
    $("input[name=destino]:checked").each(function(){
        values.push($(this));
    });

    $(values).each(function(i,element) {
        var _this = $(this);
        _this.attr('name','origen');
        _this.parent().parent().appendTo('#noAsignados');
        full_data[_this.val()-1].noasignado = true;
    });
    values.length=0;
    $paginacion.children().remove();
    loadSintomasSource(full_data);

    // SoleS Logic
    show_diseases();
}

function showDescription(data){
    swal(data);
}

function show_diseases() {
    var asignados = document.getElementById('asignados').children;
    var len = asignados.length;
    var symptoms = [];

    for (var i=0; i<len; i++)
        symptoms.push(asignados[i].getAttribute('data-detalle'));

    var $diseases = $('#enfermedades');

    $.ajax({
        url: $('#answer').attr('url');,
        data: {symptoms: JSON.stringify(symptoms)},
        method: 'GET'
    }).done(function (data) {
        if (data.error) {
            $diseases.html('');
            showmessage(data.message,1);
        }
        else {
            $diseases.html('');

            //<img  class="img-thumbnail img-rounded" src="./symptoms/images/'+data[i].imagen+'" style="height: 100px"/>
            id = [];
            name_array = [];
            var image = [];
            var video = [];
            var description = [];

            $.each(data.id, function (key, value) {
                id.push(value);
            });
            $.each(data.name, function (key, value) {
                name_array.push(value);
            });
            $.each(data.image, function (key, value) {
                image.push(value);
            });
            $.each(data.video, function (key, value) {
                video.push(value);
            });
            $.each(data.description, function (key, value) {
                description.push(value);
            });

            for (var i = 0; i < id.length; i++) {
                var html_ =
                    '<div class="col-md-6">' +
                    '<div class="card text-center" style="background-color: #4e4e4e; border-color: #151515; color:white;">' +
                    '<div class="card-block">' +
                    '<h3 class="card-title">' + name_array[i] + '</h3>' +
                    '<button type="button" class="btn btn-success" onclick="mostrarDetalles(\''+name_array[i]+'\',\''+description[i]+'\',\''+image[i]+'\');">' +
                    '<i class="fa fa-eye"></i> Ver enfermedad' +
                    '</button>' +
                    '<button type="button" class="btn btn-success" onclick="mostrarTratamiento(\''+name_array[i]+'\',\''+ id[i]+'\',\''+ video[i]+'\' );">' +
                    '<i class="fa fa-eye"></i> Ver tratamiento' +
                    '</button>' +
                    '<br><br>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                $diseases.append(html_);
            }
        }
    });
}

function mostrarDetalles(name,description,image){
    event.preventDefault();

    $('#name_disease').html(name);
    $('#description_disease').val(description);
    var html = '<img  class="img-rounded image" src="./diseases/images/'+image+'">';
    $('#image_disease').html(html);

    $modalDetalles.modal('show');
}

function mostrarTratamiento(name,id,video){
    event.preventDefault();
    $('#name_disease_treatment').html(name);
    $('#iframe').attr('src',video);

    $.ajax({
        url: '../public/enfermedades/medicamentos/'+id,
        method: 'GET'
    }).done(function (data) {
        if (data.error)
            $('#medication').html('Ho existen datos');
        else
        {
            $('#medication').html('');
            $.each(data.medication, function (key, value) {
                $('#medication').append('<p><i class="fa fa-list-ul"></i> '+value+'</p>');
            });
        }
    });

    $modalTratamiento.modal('show');
}

function showmessage( message, error ){
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
        timer: 400
    });
}

// Associative array length
Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function diagnose() {
    // Evaluate each possible disease
    // and get their symptoms list
    var symptoms = [];
    for (var i=0; i<id.length; ++i) {
        var disease_id = id[i];
        $.getJSON('./enfermedades/'+disease_id+'/sintomas', function (data) {
            symptoms[data.disease_id] = data.symptom_ids;

            // When all the symptoms were loaded
            if (Object.size(symptoms) == id.length) {
                startDiagnoseQuestions(symptoms);
            }
        });
    }
}

function startDiagnoseQuestions(symptoms_group) {
    diagnoseDisease(0, symptoms_group);

    // Loaded symptoms
    // console.log(symptoms);
}

function diagnoseDisease(diagnose_position, symptoms_group) {
    // They id array contains the ids for possible diseases
    if (diagnose_position == id.length) return;

    // alert('preguntas para D position ' + diagnose_position);

    var disease_id = id[diagnose_position];
    var name = name_array[diagnose_position];
    var symptoms = symptoms_group[disease_id];

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
                title: 'Usted presenta este síntoma?',
                text: 'Síntoma: ' + symptomName(symptoms[i])
            });
        }
    }

    swal.queue(steps).then(function () {
        // console.log(name);
        swal({
            title: 'Usted presenta: '+name+  'o posiblemente ',
            confirmButtonText: 'Entiendo !',
            showCancelButton: false
        }).finally(function() {
            swal.resetDefaults();
            diagnoseDisease(diagnose_position+1, symptoms_group);
        });;

    }, function () {
        // console.log(name);
        swal({
            title: 'Se ha descartado la enfermedad: '+name,
            confirmButtonText: 'Entiendo !',
            showCancelButton: false
        }).finally(function() {
            swal.resetDefaults();
            diagnoseDisease(diagnose_position+1, symptoms_group);
        });;
    })

}

function symptomName(symptom_id) {
    for (var i=0; i<full_data.length; ++i) {
        if (full_data[i].id == symptom_id)
            return full_data[i].name;
    }

    return 'Symptom ' + symptom_id;
}

function notSelectedSymptom(symptom_id) {
    for (var i=0; i<full_data.length; ++i) {
        if (full_data[i].id == symptom_id)
            return full_data[i].noasignado;
    }

    return true;
}