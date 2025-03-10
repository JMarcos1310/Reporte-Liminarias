<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
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




// Ruta para mostrar el formulario de login
Route::get('/admin/login', function () {
    return view('administrador.login');
})->name('admin.login');

// Ruta para procesar el formulario de login
Route::post('/admin/login', function () {
    $credentials = request()->only('email', 'contraseña');

    $admin = DB::table('administradores')->where('email', $credentials['email'])->first();

    if ($admin && Hash::check($credentials['contraseña'], $admin->contraseña)) {
        session(['admin_id' => $admin->id]);
        return redirect()->route('admin.dashboard')->with('success', 'Login exitoso');
    } else {
        return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
    }
})->name('admin.login.submit');

// Ruta para el dashboard del administrador (protegida)
Route::get('/admin/dashboard', function () {
    if (!session('admin_id')) {
        return redirect()->route('admin.login')->with('error', 'Por favor inicie sesión');
    }

    return view('administrador.dashboard');
})->name('admin.dashboard');

// Ruta para cerrar sesión
Route::get('/admin/logout', function () {
    session()->forget('admin_id');
    return redirect()->route('admin.login')->with('success', 'Sesión cerrada correctamente');
})->name('admin.logout');