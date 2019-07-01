
@extends('layouts.general')

@section('title', 'Pacientes')

@section('styles')
    <style>
        .margen
        {
            margin-top:11px;
        }
        .no-resize
        {
            resize: none;
        }
        .inside:focus{
            border: 1px solid #0097cf;
        }
        img
        {
            height: 40px;
            width: 40px;
        }
    </style>
    <link rel="stylesheet" href="{{asset('assets/css/footable.bootstrap.min.css')}}">
@endsection

@section('content')

    @if (session('mensaje'))
      <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        </button>
        <h3>{{ session('mensaje') }}</h3>
      </div>
    @endif

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <button type="button" id="btnNew" class="btn btn-info btn-fill btn-wd">Nuevo paciente</button>
                    <br>
                    <br>
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Listado de pacientes</h4>
                        </div>

                        @if( $errors->count() > 0 )
                            <div class="col-sm-12">
                                <div class="alert alert-danger" role="alert">
                                    <strong>Lo sentimos! </strong>Por favor revise los siguientes errores.
                                    @foreach($errors->all() as $message)
                                        <p>{{$message}}</p>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="content">
                            <table class="table table-striped table-bordered" id="example2">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Direccion</th>
                                        <th>Correo</th>
                                        <th>Estatus</th>
                                        <th data-hide="all" data-breakpoints="all">Ciudad</th>
                                        <th data-hide="all" data-breakpoints="all">País</th>
                                        <th data-type="html">Imagen</th>
                                        <th data-hide="all" data-breakpoints="all">Fecha de nacimiento</th>
                                        <th data-type="html">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                    <tr>
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->surname }}</td>
                                        <td>{{ $patient->address }}</td>
                                        <td>{{ $patient->email }}</td>
                                        <td>{{ $patient->status ? 'Activo' : 'Inactivo'}}</td>
                                        <td>{{ $patient->city }}</td>
                                        <td>{{ $patient->country }}</td>
                                        <td><img src="{{ asset('patient/images') }}/{{ $patient->image }} " class="image"></td>
                                        <td>{{ $patient->birthdate }}</td>
                                        <td>
                                            @if($patient->status)
                                            <button type="button" class="btn btn-success" data-cita="{{ $patient->id }}"
                                                    data-name="{{ $patient->name }}"
                                                    data-surname="{{ $patient->surname }}"><i class="ti-alarm-clock" data-backdrop="false"></i> Agendar Cita</button>
    
                                                    <button type="button"  class="btn btn-info" data-notificacion="{{ $patient->id }}" data-name="{{ $patient->name }}"
                                                    data-surname="{{ $patient->surname }}"><i class="ti-alarm-clock"></i>Notificar proxima cita</button>
                                                                                            
                                                    <button type="button"  class="btn btn-primary" data-recipe="{{ $patient->id }}"><i class="ti-alarm-clock"></i>Enviar recipe medico</button>
                                            @endif
                                                    <button type="button" class="btn btn-primary" data-id="{{ $patient->id }}"
                                                    data-name="{{ $patient->name }}"
                                                    data-surname="{{ $patient->surname }}"
                                                    data-address="{{ $patient->address }}"
                                                    data-email="{{ $patient->email }}"
                                                    data-city="{{ $patient->city }}"
                                                    data-country="{{ $patient->country }}"
                                                    data-image="{{ $patient->image }}"
                                                    data-birthdate="{{ $patient->birthdate }}"
                                                    data-comment="{{ $patient->comment }}"><i class="fa fa-pencil" data-backdrop="false"></i></button>
                                            <button type="button"  class="btn btn-danger" data-delete="{{ $patient->id }}" data-name="{{ $patient->name }}" data-surname="{{ $patient->surname }}" data-backdrop="false"><i class="fa fa-trash"></i></button>
                                            
                                            @if($patient->status)
                                                <button type="button"  class="btn btn-danger" data-deactivate="{{ $patient->id }}" data-name="{{ $patient->name }}" data-surname="{{ $patient->surname }}" data-backdrop="false"><i class="fa fa-chevron-left"></i>
                                                    Desactivar paciente
                                                </button>
                                            @endif

                                            @if(!$patient->status)
                                                <button type="button"  class="btn btn-primary" data-activate="{{ $patient->id }}" data-name="{{ $patient->name }}" data-surname="{{ $patient->surname }}" data-backdrop="false"><i class="fa fa-chevron-left"></i>
                                                    Activar paciente
                                                </button>
                                            @endif
                                            
                                            @if($patient->status)
                                                <a href="{{url('diagnostico-'.$patient->id)}}" class="btn btn-info"><i class="fa fa-eye" data-backdrop="false"></i> Diagnosticar</a>
                                            @endif
                                            <button type="button"  class="btn btn-success" data-diagnosticos="{{ $patient->id }}" data-name="{{ $patient->name }}" data-surname="{{ $patient->surname }}" data-backdrop="false"><i class="fa fa-eye"></i>Historial</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $patients->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalNuevo" class="modal fade in">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nuevo paciente</h4>
                </div>


                <form id="formRegistrar" action="{{ url('/pacientes/registrar') }}" class="form-horizontal form-label-left"  method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <div class="form-group">
                            <label class="control-label col-md-3" for="name">Nombres <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="name" name="name" required="required" class="form-control inside">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="surname">Apellidos <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="surname" name="surname" class="form-control inside" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="address">Dirección <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="address" name="address" class="form-control inside" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="email">Correo electronico <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="email" id="email" name="email" class="form-control inside" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="city">Ciudad <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="city" name="city" class="form-control inside" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="country">País <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="country" name="country" class="form-control inside" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3"  for="image">Nueva Imagen</label>
                            <div class="col-md-5">
                                <input type="file" name="image" class="form-control inside" accept="image/*">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="comment">Comentario </label>
                            <div class="col-md-8">
                                <input type="text" id="comment" name="comment" class="form-control inside">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="birthdate">Fecha de nacimiento <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="date" id="birthdate" name="birthdate" class="form-control inside" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button class="btn btn-danger" data-dismiss="modal"><span class="ti-close"></span> Cancelar</button>
                        <button class="btn btn-primary"><span class="ti-save" aria-hidden="true"></span> Guardar paciente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalRecipe" class="modal fade in">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Enviar recipe paciente</h4>
                </div>


                <form id="formRecipe" action="{{ url('/recipe/mail') }}" class="form-horizontal form-label-left"  method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" />

                        <div class="form-group">
                            <label class="control-label col-md-3"  for="image">Adjuntar recipe</label>
                            <div class="col-md-5">
                                <input type="file" name="image" class="form-control inside" accept="image/*" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button class="btn btn-danger" data-dismiss="modal"><span class="ti-close"></span> Cancelar</button>
                        <button class="btn btn-primary"><span class="ti-save" aria-hidden="true"></span> Enviar recipe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalEditar" class="modal fade in">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar paciente</h4>
                </div>


                <form id="formEditar" action="{{ url('/pacientes/modificar') }}" class="form-horizontal form-label-left"  method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" />

                        <div class="form-group">
                            <label class="control-label col-md-3" for="name">Nombres <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="name" name="name" required="required" class="form-control inside">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="surname">Apellidos <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="surname" name="surname" class="form-control inside">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="address">Dirección <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="address" name="address" class="form-control inside">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="email">Correo electronico <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="email" id="email" name="email" class="form-control inside" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="city">Ciudad <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="city" name="city" class="form-control inside">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="country">País <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="country" name="country" class="form-control inside">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3"  for="image">Nueva Imagen</label>
                            <div class="col-md-5">
                                <input type="file" name="image" class="form-control inside" accept="image/*">
                            </div>
                            <label class="control-label col-md-2" for="last-name">Imagen anterior</label>
                            <div class="col-md-2" id="newImage">
                                <input type="hidden" name="oldImage">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="comment">Comentario <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" id="comment" name="comment" class="form-control inside">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3" for="birthdate">Fecha de nacimiento <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="date" id="birthdate" name="birthdate" class="form-control inside">
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button class="btn btn-danger" data-dismiss="modal"><span class="ti-close"></span> Cancelar</button>
                        <button class="btn btn-primary"><span class="ti-save" aria-hidden="true"></span> Guardar paciente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalDiagnosticos" class="modal fade in" data-url="{{url('reporte/diagnostico')}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Diagnósticos del paciente:  <i id="nombre" ></i> </h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Listado de diagnósticos</h4>
                                </div>
                                <div class="content">
                                    <table class="table table-hover" id="tabla-diagnostico" data-url="{{url('historial/mail')}}"> 
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Diagnóstico</th>
                                            <th>Médico</th>
                                            <th>Fecha</th>
                                            <th>Opciones</th>
                                            <th>Enviar</th>
                                        </tr>
                                        </thead>
                                        <template id="template-diagnosis">
                                            <tr>
                                                <td data-id></td>
                                                <td data-diagnosis></td>
                                                <td data-user></td>
                                                <td data-date></td>
                                                <td data-option></td>
                                                <td data-envio></td>
                                            </tr>
                                        </template>
                                        <tbody id="table-diagnosis">
                                        {{-- Load with javascript --}}

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-danger" data-dismiss="modal"><span class="ti-close"></span> Cancelar</button>
                </div>

            </div>
        </div>
    </div>

        <div id="modalNotificar" class="modal fade in" data-url="{{url('reporte/diagnostico')}}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Proxima cita del paciente:  <i id="nombre" ></i> </h4>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Cita</h4>
                                </div>
                                <div class="content">
                                    <table class="table table-hover" id="table-appointments" data-url="{{url('appointment/mail')}}"> 
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                            <th>Opciones</th>
                                        </tr>
                                        </thead>
                                        <template id="template-appointments">
                                            <tr>
                                                <td data-id></td>
                                                <td data-date></td>
                                                <td data-hour></td>
                                                <td data-option></td>
                                            </tr>
                                        </template>
                                        <tbody id="table-cita">
                                        {{-- Load with javascript --}}

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-danger" data-dismiss="modal"><span class="ti-close"></span> Cancelar</button>
                </div>

            </div>
        </div>
    </div>

    <div id="modalEliminar" class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar paciente</h4>
                </div>
                <form id="formEliminar" action="{{ url('pacientes/eliminar') }}" method="POST">
                    <div class="modal-body">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" />
                        <div class="form-group">
                            <label for="nombreEliminar">¿Desea eliminar el siguiente paciente?</label>
                            <input type="text" readonly class="form-control" name="nombreEliminar"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group pull-left">
                            <button class="btn btn-danger pull-left" data-dismiss="modal"><span class="ti-close"></span> Cancelar</button>
                        </div>
                        <div class="btn-group pull-right">
                            <button class="btn btn-primary"><span class="ti-check" aria-hidden="true"></span> Aceptar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalDesactivar" class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Desactivar paciente</h4>
                </div>
                <form id="formDesactivar" action="{{ url('pacientes/desactivar') }}" method="POST">
                    <div class="modal-body">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" />
                        <div class="form-group">
                            <label for="nombreEliminar">¿Desea desactivar el siguiente paciente?</label>
                            <input type="text" readonly class="form-control" name="nombreDesactivar"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group pull-left">
                            <button class="btn btn-danger pull-left" data-dismiss="modal"><span class="ti-close"></span> Cancelar</button>
                        </div>
                        <div class="btn-group pull-right">
                            <button class="btn btn-primary"><span class="ti-check" aria-hidden="true"></span> Aceptar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalActivar" class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Activar paciente</h4>
                </div>
                <form id="formActivar" action="{{ url('pacientes/activar') }}" method="POST">
                    <div class="modal-body">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" />
                        <div class="form-group">
                            <label for="nombreEliminar">¿Desea activar nuevamente el siguiente paciente?</label>
                            <input type="text" readonly class="form-control" name="nombreActivar"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group pull-left">
                            <button class="btn btn-danger pull-left" data-dismiss="modal"><span class="ti-close"></span> Cancelar</button>
                        </div>
                        <div class="btn-group pull-right">
                            <button class="btn btn-primary"><span class="ti-check" aria-hidden="true"></span> Aceptar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalCita" class="modal fade in">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registrar cita</h4>
                </div>

                <div class="modal-body">
                    <form id="formCita" action="{{ url('citas/registrar') }}" class="form-horizontal form-label-left"  method="POST">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" />

                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Paciente *</label>
                                <select class="form-control" name="patient_id" placeholder="Seleccionar" required>
                                    <option value="null" selected>Seleccionar</option>
                                  @foreach($patients as $patient)
                                    <option value="{{$patient->id}}">{{$patient->name}} {{$patient->surname}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Fecha *</label>
                                <input type="date" class="form-control inside" name="date" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6">
                                <label>Hora *</label>
                                <input type="time" class="form-control inside in-input" name="hour" required>
                            </div>
                        </div>


                        <div class="form-group text-center">
                            <div class="col-md-12">
                                <button class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>
                                <button class="btn btn-primary"><span class="glyphicon glyphicon-ok-circle"></span> Registrar </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('assets/js/footable.min.js') }}"></script>
    <script src="{{ asset('patient/js/patient.js') }}"></script>
    <script>
        $(document).ready(function(){

            $('#example2').DataTable({
              'paging'      : true,
              'lengthChange': true,
              'searching'   : true,
              'ordering'    : false,
              'info'        : true,
              'autoWidth'   : true,
              "language": {
                    "lengthMenu": "Mostrar _MENU_",
                    "search": "Buscar",
                    "info": "Mostrar pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No exixten registros",
                    'Previus': 'Anterior',
                    "emptyTable": 'No hay datos',
                    "paginate": {
                        "first":      "Primero",
                        "last":       "Ultimo",
                        "next":       "Próximo",
                        "previous":   "Anterior"
                    }, 

                }
            })

        }) 
          
    </script>
@endsection