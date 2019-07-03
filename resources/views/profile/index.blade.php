@extends('layouts.general')

@section('title','Perfil del paciente')

@section('styles')
    <style>
        .separator
        {
            margin-top: 15px;
        }
        .margen
        {
            margin-top:30px;
        }
        .no-resize
        {
            resize: none;
        }
        .in-input
        {
            border: 1px solid rgba(102, 97, 91, 0.35) !important;
        }
        .inside:focus{
            border: 1px solid #0097cf !important;
        }
        .image
        {
            height: 40px;
            width: 40px;
        }
    </style>

    <link rel="stylesheet" href="{{asset('assets/css/footable.bootstrap.min.css')}}">
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <h2>Perfil del paciente {{$patient->name}} {{$patient->surname}}</h2>
                </div>
                <div class="col-md-2">   
                    <img src="{{asset('patient/images/'.$patient->image)}}" height="80px" width="80px" class="pull right">
                </div>
            </div>
            
            <br>
            <div class="row">
                <div class="col-md-4">
                    <p><h4>Dirección:</h4> {{$patient->address}} </p>
                </div>
                <div class="col-md-4">
                    <p><h4>Correo:</h4> {{$patient->email}} </p>
                </div>
                <div class="col-md-4">
                    <p><h4>Ciudad:</h4> {{$patient->city}} </p>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4">
                    <p><h4>Pais:</h4> {{$patient->country}} </p>
                </div>
                <div class="col-md-4">
                    <p><h4>Comentario:</h4> {{$patient->comment}} </p>
                </div>
                <div class="col-md-4">
                    <p><h4>Fecha de nacimento:</h4> {{$patient->birthdate}} </p>
                </div>
            </div>

            <br>
            
            @if(isset($patient->history))
            <div class="row">
                <div class="col-md-12">
                    <h3>Diagnosticos realizados: {{count($patient->history)}}</h3>
                </div>
            </div>
            
            <br>

            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-2">
                    <h4 style="text-decoration: underline;">Ultimo diagnostico</h4>
                </div>
                <div class="col-md-4"></div>
            </div>
            
            <br>

            <div class="row">
                <div class="col-md-4">
                    <p><h4>Fecha:</h4> {{$patient->history[count($patient->history) - 1]->date}} </p>
                </div>
                <div class="col-md-4">
                    <p><h4>Enfermedad:</h4> {{$patient->history[count($patient->history) - 1]->rules[0]->diseases->name}} </p>
                </div>
                <div class="col-md-4">
                    <p><h4>Medico encargado:</h4> {{$patient->history[count($patient->history) - 1]->users[0]->name}} </p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    
                @if($patient->history[count($patient->history) - 1]->recipe != null)
                    <h4>Recipe</h4>
                    <img src="{{asset('patient/images/'.$patient->history[count($patient->history) - 1]->recipe)}}" height="400px" width="400px" class="pull right">
                @endif
                </div>
                <div class="col-md-4"></div>
            </div>
           
            @else

            <div class="row">
                <div class="col-md-12">
                    <h3><b>Diagnosticos realizados:</b> 0</h3>
                </div>
            </div>
    
            @endif
            
            <br>

            <div class="row">
                <div class="col-md-12">
                    <h3>Proxima cita: </h3>
                </div>
            </div>

            <br>
            
            @if($appointment != null)

                <div class="row">
                    <div class="col-md-4">
                        <p><h4>Fecha:</h4> {{$appointment->date}} </p>
                    </div>
                    <div class="col-md-4">
                        <p><h4>Hora:</h4> {{$appointment->hour}} </p>
                    </div>
                </div>
            
            @else

                <div class="row">
                    <div class="col-md-4"><h4><b>Ninguna</b></h4></div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4"></div>
                </div>

            @endif
        </div>
    </div>

   {{--  <div id="modalRegistrar" class="modal fade in">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registrar citas</h4>
                </div>

                <div class="modal-body">
                    <form id="formRegistrar" action="{{ url('citas/registrar') }}" class="form-horizontal form-label-left"  method="POST">
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
                                <label>Hora * </label>
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

    <div id="modalEditar" class="modal fade in">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar cita</h4>
                </div>

                <div class="modal-body">
                    <form id="formModificar" action="{{ url('citas/modificar') }}" class="form-horizontal form-label-left"  method="POST">
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
                                <button class="btn btn-primary"><span class="glyphicon glyphicon-ok-circle"></span> Modificar </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEliminar" class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar cita</h4>
                </div>
                <form id="form-delete" action="{{ url('/citas/eliminar') }}" method="POST">
                    <div class="modal-body">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" />
                        <div class="form-group">
                            <label for="nombreEliminar">¿Desea eliminar la siguiente cita?</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group text-center">
                            <button class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-menu-up"></span> Cancelar</button>
                            <button type="button" class="btn btn-primary" id="accept"><span class="glyphicon glyphicon-ok-circle"></span> Aceptar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/footable.min.js') }} "></script>
    <script src="{{ asset('assets/js/search.js') }} "></script>
    <script src="{{ asset('appoinment/index.js') }}"></script>
        <script>
        $(document).ready(function(){

        }) 
    </script>
@endsection