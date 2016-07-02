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
                        <h2><a href="{{ url('enfermedad/nueva') }}" class="btn btn-success"><i class="fa fa-plus-square-o"></i> Nueva enfermedad</a></h2>
                    </div>
                    <div class="col-md-9 form-inline margen">
                        <div class="col-md-8 input-group ">
                            <span class="input-group-addon">Enfermedad</span><input type="text" id="search" class="form-control" placeholder="Búsqueda personalizada ...">
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
                            <h4 class="title">Listado de enfermedades</h4>
                        </div>
                        <div class="content table-responsive table-full-width">
                            <table class="table table-striped mytable">
                                <thead>
                                <tr>
                                    <th>Enfermedad</th>
                                    <th data-type="html"> Imagen </th>
                                    <th data-hide="all" data-breakpoints="all" data-title="Descripción"></th>
                                    <th data-type="html">Opciones</th>
                                </tr>
                                </thead>
                                <tbody id="tabla">
                                @foreach($diseases as $disease)
                                    <tr>
                                        <td>{{ $disease->name }}</td>
                                        <td ><img src="{{ asset('diseases/images') }}/{{ $disease->image }} " class="img-responsive image"></td>
                                        <td>{{$disease->description}}</td>
                                        <td>
                                            <button type="button" class="btn btn-success" data-edit="{{ $disease->id }}" data-name="{{ $disease ->name }}" data-description="{{ $disease->description }}" data-image="{{ $disease ->image }}">
                                                <i class="fa fa-pencil"></i>Editar
                                            </button>
                                            <button type="button" class="btn btn-danger"  data-delete="{{ $disease->id }}" data-name="{{ $disease->name }}">
                                                <i class="fa fa-trash"></i>Eliminar
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $diseases->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalEditar" class="modal fade in">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar enfermedad</h4>
                </div>

                <div class="modal-body">
                    <form id="form" action="{{ url('enfermedad/modificar') }}" class="form-horizontal form-label-left"  method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" />

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Nombre*</label>
                                <input type="text" id="name" name="name" class="form-control inside in-input" required>
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="image">Nueva Imagen</label>
                                <input type="file" id="image" name="image" class="form-control inside in-input" accept="image/*">
                            </div>
                        </div>

                        <div class="col-md-3 col-md-offset-1">
                            <div class="form-group">
                                <label for="oldImage">Imagen anterior</label>
                                <div id="oldImage"> </div>
                                <input type="hidden" name="oldImage">
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea name="description" id="description" class="form-control no-resize inside in-input"></textarea>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>
                            <button class="btn btn-primary"><span class="glyphicon glyphicon-ok-circle"></span> Guardar cambios</button>
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
                    <h4 class="modal-title">Eliminar enfermedad</h4>
                </div>
                <form id="form-delete" action="{{ url('enfermedad/eliminar') }}" method="POST">
                    <div class="modal-body">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="id" />
                        <div class="form-group">
                            <label for="nombreEliminar">¿Desea eliminar la siguiente enfermedad?</label>
                            <input type="text" readonly class="form-control in-input" name="nombreEliminar"/>
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
    <script src="{{ asset('diseases/js/index.js') }}"></script>
@endsection