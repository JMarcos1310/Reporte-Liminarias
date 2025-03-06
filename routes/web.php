<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Ruta para crear peticiones
Route::get('/peticiones/create', function () {
    $comunidades = DB::table('comunidades')->get();
    $tiposServicio = DB::table('tipos_servicio')->get();
    return view('peticiones.create', compact('comunidades', 'tiposServicio'));
});

// Ruta para guardar peticiones
Route::post('/peticiones/store', function () {
    $data = request()->all();
    $numeroSolicitud = 'SOL-' . uniqid();

    // Insertar la petición en la base de datos
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

    return redirect('/peticiones/create')->with('success', 'Solicitud enviada correctamente.');
})->name('peticiones.store');

// Ruta para el dashboard del administrador
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// Ruta para ver peticiones (admin)
Route::get('/admin/peticiones', function () {
    $peticiones = DB::table('peticiones')->get();
    return view('admin.peticiones', compact('peticiones'));
})->name('admin.peticiones');

// Ruta para generar PDF de peticiones (admin)
Route::get('/admin/peticiones/pdf', function () {
    $peticiones = DB::table('peticiones')->get();
    $pdf = Pdf::loadView('admin.peticiones_pdf', compact('peticiones'));
    return $pdf->download('peticiones.pdf');
})->name('admin.peticiones.pdf');



Route::get('/admin/peticiones/pdf', function () {
    $peticiones = DB::table('peticiones')->get();
    $pdf = Pdf::loadView('admin.peticiones_pdf', compact('peticiones'));
    return $pdf->download('peticiones.pdf');
})->name('admin.peticiones.pdf');

// Ruta para el dashboard del servidor público
Route::get('/servidor/publico/dashboard', function () {
    return view('servidor_publico.dashboard');
})->name('servidor_publico.dashboard');

// Ruta para ver peticiones (servidor público)
Route::get('/servidor/publico/peticiones', function () {
    $peticiones = DB::table('peticiones')->get();
    return view('servidor_publico.peticiones', compact('peticiones'));
})->name('servidor_publico.peticiones');


// Ruta para mostrar el formulario de creación de peticiones (servidor público)
Route::get('/servidor/publico/peticiones/create', function () {
    $comunidades = DB::table('comunidades')->get();
    $tiposServicio = DB::table('tipos_servicio')->get();
    return view('servidor_publico.peticiones_create', compact('comunidades', 'tiposServicio'));
})->name('servidor_publico.peticiones.create');

// Ruta para guardar peticiones (servidor público)
Route::post('/servidor/publico/peticiones/store', function () {
    $data = request()->all();
    $numeroSolicitud = 'SOL-' . uniqid();

    // Insertar la petición en la base de datos
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

    return redirect('/servidor/publico/peticiones/create')->with('success', 'Solicitud enviada correctamente.');
})->name('servidor_publico.peticiones.store');