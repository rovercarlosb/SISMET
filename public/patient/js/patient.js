$(document).on('ready', principal);

function principal(){
    $('.mytable').footable();

    $modalNuevo = $('#modalNuevo');
    $modalEditar = $('#modalEditar');
    $modalEliminar = $('#modalEliminar');
    $modalDesactivar = $('#modalDesactivar');
    $modalActivar = $('#modalActivar');
    $modalDiagnosticos = $('#modalDiagnosticos');
    $modalCita = $('#modalCita');
    $modalNotificar = $('#modalNotificar');
    $modalRecipe = $('#modalRecipe');

    $('[data-id]').on('click', mostrarEditar);
    $('[data-cita]').on('click', mostrarCita);
    $('[data-delete]').on('click', mostrarEliminar);
    $('[data-deactivate]').on('click', mostrarDesactivar);
    $('[data-activate]').on('click', mostrarActivar);
    $('[data-diagnosticos]').on('click', showDiagnosticos);
    $('[data-notificacion]').on('click', showCita);
    $('[data-recipe]').on('click', showRecipe);

    $('#formEditar').on('submit', updatePatient);
    $('#formRegistrar').on('submit', registerPatient);
    $('#formEliminar').on('submit', deletePatient);
    $('#formDesactivar').on('submit', deactivatePatient);
    $('#formActivar').on('submit', activatePatient);
    $('#formCita').on('submit',registerCita);
    $('#formRecipe').on('submit',sendRecipe);

    $('#btnNew').on('click', mostrarNuevo);
}

var $modalDiagnosticos, $modalNotificar;

function mostrarCita(){

    var id = $(this).data('cita');

    $modalCita.find('[name="patient_id"]').val(id);

    $modalCita.modal('show');

}

function showRecipe(){

    var id = $(this).data('recipe');

    $modalRecipe.find('[name="id"]').val(id);

    $modalRecipe.modal('show');

}


function registerCita(){

    event.preventDefault();

    $.ajax({
            url: $(this).attr("action"),
            data: new FormData(this),
            dataType: "JSON",
            processData: false,
            contentType: false,
            method: 'POST'
        })
        .done(function( response ) {
            if(response.error)
                showmessage(response.message,1);
            else{
                showmessage(response.message,0);
                setTimeout(function(){
                    location.reload();
                }, 2000);
            }
        });
}

function showDiagnosticos() {
    var name = $(this).data('name');
    var surname = $(this).data('surname');
    var id = $(this).data('diagnosticos');
    $modalDiagnosticos.find('[id="nombre"]').html(name+" "+surname);
    
    $.getJSON('diagnosis/patient/'+id, function (data) {
        $('#table-diagnosis').html("");
        for ( var i=0; i<data.length; ++i ) {
            renderTemplateDiagnosis(data[i].id,data[i].rules[0].disease_name, data[i].rules[0].percentage, data[i].users[0].name, data[i].created_at);
        }
        console.log(data);
    });
    $modalDiagnosticos.modal('show');
}

function showCita() {
    var name = $(this).data('name');
    var surname = $(this).data('surname');
    var id = $(this).data('notificacion');
    $modalNotificar.find('[id="nombre"]').html(name+" "+surname);
    
    
    $.getJSON('appointment/patient/'+id, function (data) {
    
        $('#table-cita').html("");

        renderTemplateAppointment(data.id,data.date, data.hour);
        console.log(data);
    });

    $modalNotificar.modal('show');
}

function activateTemplate(id) {
    var t = document.querySelector(id);
    return document.importNode(t.content, true);
}

function renderTemplateDiagnosis(id_history,diagnosis, percentage, user,date) {
    var clone = activateTemplate('#template-diagnosis');

    let url = $("#modalDiagnosticos").data('url')+'/'+id_history;
    let url_email = $('#tabla-diagnostico').data('url')+'/'+id_history;

    clone.querySelector("[data-id]").innerHTML = id_history;
    clone.querySelector("[data-diagnosis]").innerHTML = diagnosis + ' ' + percentage +' %';
    clone.querySelector("[data-user]").innerHTML = user;
    clone.querySelector("[data-date]").innerHTML = date;
    clone.querySelector("[data-option]").innerHTML = '<a class="btn btn-primary" target="_blank">Descargar PDF</a>';
    clone.querySelector("[data-envio]").innerHTML = '<a class="btn btn-success" id="correo">Enviar por correo</a>';

    clone.querySelector("a").setAttribute('href',url);
    clone.querySelector("a[id='correo']").setAttribute('href', url_email);

    $('#table-diagnosis').append(clone);
}

function renderTemplateAppointment(id_appointment,date, hour) {

    var clone_appointment = activateTemplate('#template-appointments');

    let email = $('#table-appointments').data('url')+'/'+id_appointment;

    clone_appointment.querySelector("[data-id]").innerHTML = id_appointment;
    clone_appointment.querySelector("[data-date]").innerHTML = date;
    clone_appointment.querySelector("[data-hour]").innerHTML = hour;
    clone_appointment.querySelector("[data-option]").innerHTML = '<a class="btn btn-success" id="appointment-mail">Notificar por correo</a>';

    clone_appointment.querySelector("a[id='appointment-mail']").setAttribute('href', email);

    $('#table-cita').append(clone_appointment);
}
function deletePatient() {
    event.preventDefault();
    var url =  $('#formEliminar').attr('action');
    console.log(url);
    $.ajax({
        url: url,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        method: 'POST'
    })
        .done(function( response ) {

            if(response.error)
                showmessage(response.message,1);
            else{
                showmessage(response.message,0);
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }
        });
}

function deactivatePatient() {
    event.preventDefault();
    var url =  $('#formDesactivar').attr('action');
    // console.log(url);
    $.ajax({
        url: url,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        method: 'POST'
    })
        .done(function( response ) {

            if(response.error)
                showmessage(response.message,1);
            else{
                showmessage(response.message,0);
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }
        });
}

function activatePatient() {
    event.preventDefault();
    var url =  $('#formActivar').attr('action');
    // console.log(url);
    $.ajax({
        url: url,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        method: 'POST'
    })
        .done(function( response ) {

            if(response.error)
                showmessage(response.message,1);
            else{
                showmessage(response.message,0);
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }
        });
}

function updatePatient() {
    event.preventDefault();
    var url =  '../public/pacientes/modificar';
    $.ajax({
        url: url,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        method: 'POST'
    })
        .done(function( response ) {

            if(response.error)
                showmessage(response.message,1);
            else{
                showmessage(response.message,0);
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }
        });
}

function sendRecipe() {
    event.preventDefault();
    var url =  $('#formRecipe').attr('action');
    $.ajax({
        url: url,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        method: 'POST'
    })
        .done(function( response ) {

            if(response.error)
                showmessage(response.message,1);
            else{
                showmessage(response.message,0);
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }
        });
}

function registerPatient() {
    event.preventDefault();
    var url =  $('#formRegistrar').attr('action');    
    $.ajax({
        url: url,
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        method: 'POST'
    })
        .done(function( response ) {

            if(response.error)
                showmessage(response.message,1);
            else{
                showmessage(response.message,0);
                setTimeout(function(){
                    location.reload();
                }, 2000);
            }
        });
}

function mostrarEditar() {

    $modalEditar = $('#modalEditar');
    var id = $(this).data('id');
    $modalEditar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    $modalEditar.find('[name="name"]').val(name);

    var description = $(this).data('surname');
    $modalEditar.find('[name="surname"]').val(description);

    var price = $(this).data('address');
    $modalEditar.find('[name="address"]').val(price);

    var email = $(this).data('email');
    $modalEditar.find('[name="email"]').val(email);

    var brand = $(this).data('city');
    $modalEditar.find('[name="city"]').val(brand);

    var exemplar = $(this).data('country');
    $modalEditar.find('[name="country"]').val(exemplar);
    
    var image = $(this).data('image');
    $modalEditar.find('[name="newImage"]').val(image);
    var image_url = '../public/patient/images/'+image;
    $("#newImage").html('<img src="'+image_url+'" class="img-responsive image"> ');

    var comment = $(this).data('comment');
    $modalEditar.find('[name="comment"]').val(comment);

    var birthdate = $(this).data('birthdate');
    $modalEditar.find('[name="birthdate"]').val(birthdate);

    $modalEditar.modal('show');
}

function mostrarEliminar() {
    var id = $(this).data('delete');
    $modalEliminar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    var surname = $(this).data('surname');
    $modalEliminar.find('[name="nombreEliminar"]').val(name+" "+surname);

    $modalEliminar.modal('show');
}

function mostrarDesactivar() {
    var id = $(this).data('deactivate');
    $modalDesactivar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    var surname = $(this).data('surname');
    $modalDesactivar.find('[name="nombreDesactivar"]').val(name+" "+surname);

    $modalDesactivar.modal('show');
}

function mostrarActivar() {
    var id = $(this).data('activate');
    $modalActivar.find('[name="id"]').val(id);

    var name = $(this).data('name');
    var surname = $(this).data('surname');
    $modalActivar.find('[name="nombreActivar"]').val(name+" "+surname);

    $modalActivar.modal('show');
}

function mostrarNuevo() {
    $modalNuevo.modal('show');
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
        timer: 300
    });
}