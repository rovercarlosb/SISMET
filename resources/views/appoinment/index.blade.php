@extends('layouts.general')

@section('title','Enfermedades')

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
                <div class="col-md-12">
                    <div class="col-md-3">
                        <h2><a data-registrar class="btn btn-success"><i class="fa fa-plus-square-o"></i> Nueva cita</a></h2>
                    </div>
                    <div class="col-md-9 form-inline margen">
                        <div class="col-md-8 input-group ">
                            <span class="input-group-addon">Filtro</span><input type="text" format id="search" class="form-control" placeholder="Búsqueda personalizada ...">
                        </div>

                        <div class="col-md-3 pull-right">
                            <a href="{{ url('home') }}" class="btn btn-dark" type="button"><i class="fa fa-lock"></i> Volver</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 separator">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Citas</h4>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table class="table table-striped mytable">
                                <thead>
                                <tr>
                                    <th>Paciente</th>
                                    <th>Fecha </th>
                                    <th>Hora </th>
                                    <th>Estatus</th>
                                    <th data-type="html">Opciones</th>
                                </tr>
                                </thead>
                                <tbody id="tabla">
                                @foreach($appoinments as $appoinment)
                                    <tr>
                                        @php
                                          $date = date_create($appoinment->hour);
                                          $fecha = date_create($appoinment->date);
                                        @endphp
                                        <td>{{ $appoinment->patient->name }}</td>
                                        <td>{{ date_format($fecha, 'd-m-Y')}}</td>
                                        <td>{{ date_format($date,'g:i A')}}</td>
                                        <td>{{$appoinment->status ? 'Activo' : 'Finalizada'}}</td>
                                        <td>
                                            <button type="button" class="btn btn-success" data-edit="{{ $appoinment->id }}" data-patient="{{ $appoinment->patient->id }}" data-date="{{ $appoinment->date }}"
                                                data-hour="{{ $appoinment->hour }}" data-status="{{$appoinment->status}}">
                                                <i class="fa fa-pencil"></i>Editar
                                            </button>
                                            <button type="button" class="btn btn-danger"  data-delete="{{ $appoinment->id }}">
                                                <i class="fa fa-trash"></i>Eliminar
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $appoinments->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalRegistrar" class="modal fade in">
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
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/footable.min.js') }} "></script>
    <script src="{{ asset('assets/js/search.js') }} "></script>
    <script src="{{ asset('appoinment/index.js') }}"></script>
@endsection