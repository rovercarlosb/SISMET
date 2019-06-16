@extends('layouts.general')

@section('home','class="active"')
@section('title', 'Dashboard')

@section('styles')
    <style>
        .card img {
            height: 400px !important;
        }

    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-6">
                    <div class="card">
                        <div class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="icon-big icon-warning text-center">
                                        <img src="{{ asset('assets/img/inicio.jpg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <hr />
                                <div class="text-center">
                                     <h3>SISMET herramienta tecnologica destinado para el control y seguimiento de enfermedades tropicales</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
