$(document).on('ready', principal);

var $modalRegistrar;
var $modalEditar;
var $modalEliminar;

function principal()
{
    $('.mytable').footable();

    $modalRegistrar = $('#modalRegistrar');
    $modalEditar = $('#modalEditar');
    $modalEliminar = $('#modalEliminar');

    $('[data-registrar]').on('click', mostrarRegistrar);
    $('[data-edit]').on('click', mostrarEditar);
    $('[data-delete]').on('click', mostrarEliminar);
    $('#formRegistrar').on('submit', disease);
    $('#formModificar').on('submit', disease);
}

function mostrarRegistrar()
{
    $modalRegistrar.modal('show');
}

function mostrarEditar() {

    var id = $(this).data('edit');
    $modalEditar.find('[name="id"]').val(id);

    var patient_id = $(this).data('patient');
    $modalEditar.find('[name="patient_id"]').val(patient_id);

    var date = $(this).data('date');
    $modalEditar.find('[name="date"]').val(date);

    var hour = $(this).data('hour');
    $modalEditar.find('[name="hour"]').val(hour);

    $modalEditar.modal('show');
}

function disease()
{
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

function mostrarEliminar() {
    var id = $(this).data('delete');
    $modalEliminar.find('[name="id"]').val(id);

    $modalEliminar.modal('show');

    $('#accept').on('click', function(event)
    {
        event.preventDefault();
        var url =  $('#form-delete').attr('action')+'/'+id;

        $.ajax({
                url: url,
                method: 'GET'
            })
            .done(function( response ) {
                if(response.error)
                    showmessage(response.message,1);
                else{
                    showmessage(response.message,0);
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            });

    });
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
        timer: 400
    });
}