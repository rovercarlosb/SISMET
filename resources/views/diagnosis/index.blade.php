@extends('layouts.general')

@section('title','Diagnóstico')

@section('styles')
    <style>
        .table
        {
            margin-left: 30px;
        }
        .marco
        {
            background-color: #95e9fe !important;
        }
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
        .sintoma{
            margin: 1.4em 0;
            height: 180px;
        }

        .typeahead,
        .tt-query,
        .tt-hint {
            line-height: 30px;
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            outline: none;
        }
        .typeahead:focus {
            border: 1px solid #0097cf;
        }
        .tt-query {
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        }
        .tt-hint {
            color: #bdbdbd;
        }
        .tt-menu {
            margin: 12px 0;
            padding: 8px 0;
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.2);
            -webkit-border-radius: 8px;
            -moz-border-radius: 8px;
            border-radius: 4px;
            -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
            -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
            box-shadow: 0 5px 10px rgba(0,0,0,.2);
            color: #000;
        }
        .tt-suggestion {
            padding: 3px 20px;
            line-height: 24px;
        }
        .tt-suggestion:hover {
            cursor: pointer;
            color: #fff;
            background-color: #0097cf;
        }
        .tt-suggestion.tt-cursor {
            color: #fff;
            background-color: #0097cf;
        }
        .tt-suggestion p {
            margin: 0;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/css/typeahead.css')}}">
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 separator">
                    <br>
                    <br>
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Diagnosticando al paciente: {{ $patientName }}</h4>
                            <input id="patienId" type="hidden" value="{{ $patientId  }}">
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">

                                <label class="control-label col-md-3" for="sintoma">
                                    Síntomas:
                                </label><br><br>
                                <div class="input-group col-md-9" style="margin-left: 10px">
                                    <div class="col-md-10"><input id="sintoma" name="sintoma" class="marco typeahead form-control" type="text"></div>
                                    <div class="col-md-2" id="data-sim" data-url="{{url('factores')}}"><button class="btn btn-success" id="sintomaAdd"><i class="fa fa-check"></i></button></div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <label class="control-label col-md-3" for="antecedente">
                                    Antecedentes:
                                </label><br><br>
                                <div class="input-group col-md-9">
                                    <div class="col-md-10"><input id="antecedente" name="antecedente" class="marco typeahead form-control" type="text"></div>
                                    <div class="col-md-2" id="data-ant" data-url="{{url('factores')}}"><button class="btn btn-success" id="antecedenteAdd"><i class="fa fa-check"></i></button></div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <label class="control-label col-md-12" for="otro">
                                    Otros factores:
                                </label><br><br>
                                <div class="input-group col-md-9">
                                    <div class="col-md-10"><input id="otro" name="otro" class="marco typeahead form-control" type="text"></div>
                                    <div class="col-md-2" id="data-otro" data-url="{{url('factores')}}"><button class="btn btn-success" id="otroAdd"><i class="fa fa-check"></i></button></div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group col-md-12">
                                    <table class="table table-hover table-condensed">
                                        <thead>
                                        <tr>
                                            <th><b>Factor</b></th>
                                            <th><b>Acción</b></th>
                                        </tr>
                                        </thead>
                                        <tbody id="factorList">

                                        </tbody>
                                    </table>

                                    <input type="hidden" id='_token' name="_token" value="{{ csrf_token() }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-md-offset-1">
                                <div class="panel panel-info">
                                    <div class="panel-heading text-center" style="font-size: 16px; font-weight: bold">Enfermedad</div>
                                    <div class="panel-body text-center "  id="answer" data-url="{{url('diagnostico/enfermedades')}}">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <form id="formDiagnostico" action="{{ url('/diagnostico/guardar') }}" class="form-horizontal form-label-left"  method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3"  for="recipe">Adjuntar recipe (opcional)</label>
                                        <div class="col-md-8">
                                            <input type="file" name="recipe" id="recipe" class="form-control inside" accept="image/*">
                                         </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <button class="btn btn-success" id="save_diagnostic">Guardar diagnostico</button>
                                </div>
                            </form>
                        </div>
                        
                        <br>

                        <div class="row">
                            <div class="col-md-3 text-center">
                                <button class="btn btn-primary" id="newDiagnostic">Nuevo diagnostico</button>
                            </div>
                            <div class="col-md-3 text-center">
                                <button class="btn btn-success" id="forwardChaining" data-timer="{{$time_start}}">Diagnosticar</button>
                            </div>

                            <div class="col-md-3 text-center">
                                <a class="btn btn-danger" href="{{ url('pacientes') }}">Volver</a>
                            </div>

                            <div class="col-md-3 text-center">
                                <button class="btn btn-info" id="disease">Agregar otra enfermedad</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalRecommendation" class="modal fade in">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Recomendaciones</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-offset-2 col-md-8" id="recommendations">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-menu-up"></span> Salir</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

     <div id="modalDisease" class="modal fade in">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Agregar otra enfermedad</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                                <label class="control-label col-md-3"  for="new_diases">Agregar enfermedad</label>
                                <div class="col-md-8">
                                    <select class="select" name="new_diases" id="new_diases">
                                        @foreach($rules as $rule)
                                            <option value="{{$rule->id}}">{{$rule->diseases->name}} {{$rule->percentage}} %</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button class="btn btn-danger" data-dismiss="modal" id="cerrar"><span class="glyphicon glyphicon-menu-up"></span> Salir</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Promise.finally support -->
    <script src="{{ asset('diagnosis/js/finally.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('diagnosis/js/forwardChaining.js') }}"></script>
    <script>
        var substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substringRegex;
                // an array that will be populated with substring matches
                matches = [];
                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');
                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        matches.push(str);
                    }
                });
                cb(matches);
            };
        };
        var symptoms = {!! $symptoms !!};
        $('#sintoma').typeahead(
                {
                    hint: true,
                    highlight: true,
                    minLength: 1
                },
                {
                    name: 'symptoms',
                    source: substringMatcher(symptoms)
                }
        );
        var antecedents = {!! $antecedents !!};
        $('#antecedente').typeahead(
                {
                    hint: true,
                    highlight: true,
                    minLength: 1
                },
                {
                    name: 'antecedents',
                    source: substringMatcher(antecedents)
                }
        );
        var others = {!! $others !!};
        $('#otro').typeahead(
                {
                    hint: true,
                    highlight: true,
                    minLength: 1
                },
                {
                    name: 'others',
                    source: substringMatcher(others)
                }
        );
    </script>
@endsection
