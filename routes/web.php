<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

// Ruta de bienvenida
Route::get('/', function () {
    $comunidades = DB::table('comunidades')->get();
    $tiposServicio = DB::table('tipos_servicio')->get();
    return view('welcome', compact('comunidades', 'tiposServicio'));
})->name('home');

// Ruta para guardar peticiones
Route::post('/peticiones/store', function () {
    $data = request()->all();
    $numeroSolicitud = 'SOL-' . uniqid();

    DB::table('peticiones')->insert([
        'nombre' => $data['nombre'],
        'tipo_servicio_id' => $data['tipo_servicio_id'],
        'comunidad_id' => $data['comunidad_id'],
        'observaciones' => $data['observaciones'],
        'email' => $data['email'],
        'telefono' => $data['telefono'],
        'direccion' => $data['direccion'],
        'colonia' => $data['colonia'],
        'numero_solicitud' => $numeroSolicitud,
        'estatus' => 'pendiente',
        'created_at' => now(),
    ]);

    // Enviar correo electrónico
    Mail::raw("Gracias por su solicitud. Su número de solicitud es: $numeroSolicitud", function ($message) use ($data) {
        $message->to($data['email'])
                ->subject('Número de Solicitud');
    });

    return redirect('/')->with('success', 'Solicitud enviada correctamente.');
})->name('peticiones.store');