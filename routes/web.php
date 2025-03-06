<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

// Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Rutas para peticiones
Route::prefix('peticiones')->group(function () {
    // Ruta para mostrar el formulario de creación de peticiones
    Route::get('/create', function () {
        $comunidades = DB::table('comunidades')->get();
        $tiposServicio = DB::table('tipos_servicio')->get();
        return view('peticiones.create', compact('comunidades', 'tiposServicio'));
    })->name('peticiones.create');

    // Ruta para guardar peticiones
    Route::post('/store', function () {
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

        return redirect('/peticiones/create')->with('success', 'Solicitud enviada correctamente.');
    })->name('peticiones.store');
});

// Rutas para el administrador
Route::prefix('admin')->group(function () {
    // Dashboard del administrador
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Listado de peticiones
    Route::get('/peticiones', function () {
        $peticiones = DB::table('peticiones')->get();
        return view('admin.peticiones', compact('peticiones'));
    })->name('admin.peticiones');

    // Generar PDF de peticiones
    Route::get('/peticiones/pdf', function () {
        $peticiones = DB::table('peticiones')->get();
        $pdf = Pdf::loadView('admin.peticiones_pdf', compact('peticiones'));
        return $pdf->download('peticiones.pdf');
    })->name('admin.peticiones.pdf');
});

// Rutas para el servidor público
Route::prefix('servidor/publico')->group(function () {
    // Dashboard del servidor público
    Route::get('/dashboard', function () {
        return view('servidor_publico.dashboard');
    })->name('servidor_publico.dashboard');

    // Listado de peticiones
    Route::get('/peticiones', function () {
        $peticiones = DB::table('peticiones')->get();
        return view('servidor_publico.peticiones', compact('peticiones'));
    })->name('servidor_publico.peticiones');

    // Formulario de creación de peticiones
    Route::get('/peticiones/create', function () {
        $comunidades = DB::table('comunidades')->get();
        $tiposServicio = DB::table('tipos_servicio')->get();
        return view('servidor_publico.peticiones_create', compact('comunidades', 'tiposServicio'));
    })->name('servidor_publico.peticiones.create');

    // Guardar peticiones
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

        return redirect('/servidor/publico/peticiones/create')->with('success', 'Solicitud enviada correctamente.');
    })->name('servidor_publico.peticiones.store');
});

// Rutas para servidores públicos (registro)
Route::prefix('servidores_publicos')->group(function () {
    // Guardar servidores públicos
    Route::post('/store', function () {
        $data = request()->all();
        $numeroSolicitud = 'SOL-' . uniqid();

        DB::table('servidores_publicos')->insert([
            'nombre' => $data['nombre'],
            'comunidad_id' => $data['comunidad_id'],
            'area_departamento' => $data['area_departamento'],
            'observaciones' => $data['observaciones'],
            'email' => $data['email'],
            'direccion' => $data['direccion'],
            'colonia' => $data['colonia'],
            'numero_solicitud' => $numeroSolicitud,
            'estatus' => 'pendiente',
            'created_at' => now(),
        ]);

        return redirect('/servidores_publicos/create')->with('success', 'Servidor público registrado correctamente.');
    })->name('servidores_publicos.store');
});

Route::get('/servidores_publicos/create', function () {
    $comunidades = DB::table('comunidades')->get();
    return view('servidores_publicos.create', compact('comunidades'));
})->name('servidores_publicos.create');

Route::get('/servidores_publicos/create', function () {
    return view('servidor_publico.peticiones_create');
})->name('servidores_publicos.create');