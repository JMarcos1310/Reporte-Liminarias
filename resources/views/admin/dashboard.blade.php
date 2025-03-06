@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard del Administrador</h1>
        <a href="{{ route('admin.peticiones') }}" class="btn btn-primary">Ver Peticiones</a>
        <a href="{{ route('admin.peticiones.pdf') }}" class="btn btn-success">Generar PDF</a>
    </div>
@endsection