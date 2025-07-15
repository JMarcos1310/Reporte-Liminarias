<?php

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

/*-------------------------------------
| RUTAS PÚBLICAS (ACCESO SIN AUTENTICACIÓN)
|-------------------------------------*/

/**
 * Muestra el formulario con errores de validación
 */
Route::get('/peticiones/create', function () {
    $comunidades = DB::table('comunidades')->get();
    $tiposServicio = DB::table('tipos_servicio')->get();
    return view('welcome', compact('comunidades', 'tiposServicio'));
})->name('peticiones.create');

/**
 * Página principal - Formulario para que los ciudadanos envíen peticiones
 * Muestra las comunidades y tipos de servicio disponibles
 */
Route::get('/', function () {
    $comunidades = DB::table('comunidades')->get();
    $tiposServicio = DB::table('tipos_servicio')->get();
    return view('welcome', compact('comunidades', 'tiposServicio'));
})->name('home');

/**
 * Procesa el formulario de peticiones ciudadanas
 * - Registra al ciudadano (si no es anónimo)
 * - Crea la petición con los datos proporcionados
 * - Envía correo de confirmación
 */
Route::post('/peticiones/store', function (Request $request) {
    try {
        // Validación más robusta
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_servicio_id' => 'required|integer|exists:tipos_servicio,id',
            'comunidad_id' => 'required|integer|exists:comunidades,id',
            'observaciones' => 'required|string|max:1000',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'required|string|max:255',
            'colonia' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'evidencia_foto' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ], [
            'evidencia_foto.required' => 'Debe subir una fotografía como evidencia',
            'evidencia_foto.image' => 'El archivo debe ser una imagen válida',
            'evidencia_foto.max' => 'La imagen no debe exceder los 5MB'
        ]);

        // Iniciar transacción para asegurar integridad de datos
        DB::beginTransaction();

        // Generar número de solicitud único
        $numeroSolicitud = 'SOL-' . date('Ymd-His') . '-' . strtoupper(substr(uniqid(), -6));

        // Procesar imagen
        $image = $request->file('evidencia_foto');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = $image->storeAs('evidencias', $imageName, 'public');

        // Registrar o actualizar ciudadano
        $ciudadano = DB::table('ciudadanos')
            ->updateOrInsert(
                ['email' => $validated['email']],
                [
                    'nombre' => $validated['nombre'],
                    'telefono' => $validated['telefono'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

        // Obtener ID del ciudadano
        $ciudadanoId = DB::table('ciudadanos')
            ->where('email', $validated['email'])
            ->value('id');

        // Crear la petición
        DB::table('peticiones')->insert([
            'servidor_id' => null,
            'ciudadano_id' => $ciudadanoId,
            'tipo_servicio_id' => $validated['tipo_servicio_id'],
            'comunidad_id' => $validated['comunidad_id'],
            'numero_solicitud' => $numeroSolicitud,
            'observaciones' => $validated['observaciones'],
            'direccion' => $validated['direccion'],
            'colonia' => $validated['colonia'],
            'latitud' => $validated['latitud'],
            'longitud' => $validated['longitud'],
            'evidencia_foto' => $imagePath,
            'estatus' => 'pendiente',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Confirmar transacción
        DB::commit();

        // Enviar correo de confirmación
        try {
            Mail::raw("Su solicitud #$numeroSolicitud ha sido recibida. Estatus: Pendiente", function ($message) use ($validated) {
                $message->to($validated['email'])
                       ->subject('Confirmación de solicitud');
            });
        } catch (\Exception $mailException) {
            \Log::error("Error enviando correo: " . $mailException->getMessage());
        }

        return redirect()
            ->route('home')
            ->with('success', 'Solicitud registrada correctamente. Número: ' . $numeroSolicitud)
            ->with('clearForm', true); // Nueva bandera para limpiar formulario

    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()
            ->withErrors($e->validator)
            ->withInput();
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Error al registrar petición: " . $e->getMessage());
        
        return back()
            ->with('error', 'Ocurrió un error al registrar la solicitud. Por favor intente nuevamente.')
            ->withInput();
    }
})->name('peticiones.store');

/*
  Muestra la ubicación de una petición en el mapa
 
Route::get('/peticiones/{numero_solicitud}/mapa', function ($numero_solicitud) {
    $peticion = DB::table('peticiones')
        ->where('numero_solicitud', $numero_solicitud)
        ->first();

    if (!$peticion) {
        return redirect()->route('home')->with('error', 'Solicitud no encontrada');
    }

    return view('mapa_peticion', compact('peticion'));
})->name('peticiones.mapa');
*/





/* 
    .............................

                RUTAS PARA SERVIDORES PUBLICOS

    .............................
*/
// Rutas de autenticación para servidores públicos
Route::get('/servidor/login', function () {
    return view('servidor.login');
})->name('servidor.login');

Route::post('/servidor/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'contraseña' => 'required'
    ]);

    $servidor = DB::table('servidores_publicos')
        ->where('email', $credentials['email'])
        ->first();

    if ($servidor && password_verify($credentials['contraseña'], $servidor->contraseña)) {
        session(['servidor_id' => $servidor->id, 'servidor_nombre' => $servidor->nombre]);
        return redirect()->route('servidor.dashboard');
    }

    return back()->withErrors([
        'email' => 'Credenciales incorrectas',
    ])->onlyInput('email');
})->name('servidor.login.submit');

// Ruta para crear nuevos servidores públicos (sin autenticación)
Route::get('/servidor/registro', function () {
    $comunidades = DB::table('comunidades')->get();
    $departamentos = DB::table('direccion_departamento')->get();
    return view('servidor.registro', compact('comunidades', 'departamentos'));
})->name('servidor.registro');

Route::post('/servidor/registro', function (Request $request) {
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|unique:servidores_publicos,email',
        'contraseña' => 'required|min:8',
        'confirmar_contraseña' => 'required|same:contraseña',
        'comunidad_id' => 'required|exists:comunidades,id',
        'departamento_id' => 'required|exists:direccion_departamento,id',
        'telefono' => 'nullable|string|max:20'
    ]);

    DB::table('servidores_publicos')->insert([
        'nombre' => $validated['nombre'],
        'email' => $validated['email'],
        'contraseña' => password_hash($validated['contraseña'], PASSWORD_BCRYPT),
        'comunidad_id' => $validated['comunidad_id'],
        'departamento_id' => $validated['departamento_id'],
        'telefono' => $validated['telefono'] ?? null,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('servidor.login')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión.');
})->name('servidor.registro.submit');

// Ruta de logout
Route::get('/servidor/logout', function () {
    session()->forget(['servidor_id', 'servidor_nombre']);
    return redirect()->route('servidor.login');
})->name('servidor.logout');

// Rutas protegidas por sesión (sin middleware)
Route::get('/servidor/dashboard', function () {
    if (!session('servidor_id')) {
        return redirect()->route('servidor.login');
    }

    $peticiones = DB::table('peticiones')
        ->where('servidor_id', session('servidor_id'))
        ->orderBy('created_at', 'desc')
        ->get();
        
    return view('servidor.dashboard', compact('peticiones'));
})->name('servidor.dashboard');

Route::get('/servidor/peticiones/nueva', function () {
    if (!session('servidor_id')) {
        return redirect()->route('servidor.login');
    }

    $tiposServicio = DB::table('tipos_servicio')->get();
    return view('servidor.nueva-peticion', compact('tiposServicio'));
})->name('servidor.peticiones.nueva');

Route::get('admin/reporte-pdf', function () {
    $filtros = request()->only('comunidad_id', 'mes');
    
    $query = DB::table('peticiones')
             ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
             ->join('tipos_servicio', 'peticiones.tipo_servicio_id', '=', 'tipos_servicio.id')
             ->leftJoin('ciudadanos', 'peticiones.ciudadano_id', '=', 'ciudadanos.id')
             ->select('peticiones.*', 'comunidades.nombre as comunidad', 'tipos_servicio.nombre as servicio', 'ciudadanos.nombre as ciudadano');
    
    if (!empty($filtros['comunidad_id'])) {
        $query->where('peticiones.comunidad_id', $filtros['comunidad_id']);
    }
    
    if (!empty($filtros['mes'])) {
        $query->whereMonth('peticiones.created_at', $filtros['mes']);
    }
    
    $solicitudes = $query->get();
    $comunidad = !empty($filtros['comunidad_id']) ? DB::table('comunidades')->find($filtros['comunidad_id'])->nombre : 'Todas';
    $mes = !empty($filtros['mes']) ? DateTime::createFromFormat('!m', $filtros['mes'])->format('F') : 'Todos';
    
    $pdf = PDF::loadView('admin.pdf.reporte', compact('solicitudes', 'comunidad', 'mes'));
    return $pdf->download('reporte-solicitudes.pdf');
})->name('admin.reporte.pdf');

Route::post('/servidor/peticiones/nueva', function (Request $request) {
    if (!session('servidor_id')) {
        return redirect()->route('servidor.login');
    }

    $servidorId = session('servidor_id');
    $validated = $request->validate([
        'tipo_servicio_id' => 'required|exists:tipos_servicio,id',
        'observaciones' => 'required|string|max:1000',
        'direccion' => 'required|string|max:255',
        'colonia' => 'required|string|max:255',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
        'evidencia_foto' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
    ]);

    $numeroSolicitud = 'SOL-' . date('Ymd-His') . '-' . strtoupper(substr(uniqid(), -6));

    $image = $request->file('evidencia_foto');
    $imageName = time() . '_' . $image->getClientOriginalName();
    $imagePath = $image->storeAs('evidencias', $imageName, 'public');

    DB::table('peticiones')->insert([
        'servidor_id' => $servidorId,
        'tipo_servicio_id' => $validated['tipo_servicio_id'],
        'comunidad_id' => DB::table('servidores_publicos')->where('id', $servidorId)->value('comunidad_id'),
        'numero_solicitud' => $numeroSolicitud,
        'observaciones' => $validated['observaciones'],
        'direccion' => $validated['direccion'],
        'colonia' => $validated['colonia'],
        'latitud' => $validated['latitud'],
        'longitud' => $validated['longitud'],
        'evidencia_foto' => $imagePath,
        'estatus' => 'pendiente',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('servidor.dashboard')->with('success', 'Solicitud creada exitosamente');
})->name('servidor.peticiones.nueva.submit');

// Ruta del Dashboard
Route::get('/servidor/dashboard', function () {
    if (!session('servidor_id')) {
        return redirect()->route('servidor.login');
    }

    $servidor = DB::table('servidores_publicos')
                ->where('id', session('servidor_id'))
                ->first();

    $peticiones = DB::table('peticiones')
                ->where('servidor_id', session('servidor_id'))
                ->orderBy('created_at', 'desc')
                ->get();

    return view('servidor.dashboard', [
        'servidor' => $servidor,
        'peticiones' => $peticiones
    ]);
})->name('servidor.dashboard');

Route::post('/servidor/peticiones/nueva', function (Request $request) {
    if (!session('servidor_id')) {
        return redirect()->route('servidor.login');
    }

    $validated = $request->validate([
        'tipo_servicio_id' => 'required|exists:tipos_servicio,id',
        'observaciones' => 'required|string|max:1000',
        'direccion' => 'required|string|max:255',
        'colonia' => 'required|string|max:255',
        'latitud' => 'required|numeric',
        'longitud' => 'required|numeric',
        'evidencia_foto' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
    ]);

    $numeroSolicitud = 'SOL-' . date('Ymd-His') . '-' . strtoupper(substr(uniqid(), -6));

    $image = $request->file('evidencia_foto');
    $imageName = time() . '_' . $image->getClientOriginalName();
    $imagePath = $image->storeAs('evidencias', $imageName, 'public');

    DB::table('peticiones')->insert([
        'servidor_id' => session('servidor_id'),
        'tipo_servicio_id' => $validated['tipo_servicio_id'],
        'comunidad_id' => DB::table('servidores_publicos')->where('id', session('servidor_id'))->value('comunidad_id'),
        'numero_solicitud' => $numeroSolicitud,
        'observaciones' => $validated['observaciones'],
        'direccion' => $validated['direccion'],
        'colonia' => $validated['colonia'],
        'latitud' => $validated['latitud'],
        'longitud' => $validated['longitud'],
        'evidencia_foto' => $imagePath,
        'estatus' => 'pendiente',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('servidor.dashboard')->with('success', 'Solicitud creada exitosamente');
})->name('servidor.peticiones.nueva.submit');

// Ruta para nueva petición
Route::get('/servidor/peticiones/nueva', function () {
    if (!session('servidor_id')) {
        return redirect()->route('servidor.login');
    }

    $tiposServicio = DB::table('tipos_servicio')->get();
    $servidor = DB::table('servidores_publicos')
                ->where('id', session('servidor_id'))
                ->first();

    return view('servidor.nueva-peticion', [
        'tiposServicio' => $tiposServicio,
        'servidor' => $servidor
    ]);
})->name('servidor.peticiones.nueva');


/*---------------------------RUTAS PARA ADMISTRADOR----------------------*/

// Rutas de autenticación
Route::prefix('admin')->group(function () {
    // Login
    Route::get('login', function () {
        return view('admin.auth.login');
    })->name('admin.login');
    
    Route::post('login', function () {
        $credentials = request()->only('email', 'password');
        
        $admin = DB::table('administradores')
                 ->where('email', $credentials['email'])
                 ->first();
        
        if ($admin && password_verify($credentials['password'], $admin->contraseña)) {
            session(['admin_id' => $admin->id, 'admin_name' => $admin->nombre]);
            return redirect()->route('admin.dashboard');
        }
        
        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    })->name('admin.login.submit');
    
    // Logout
    Route::post('logout', function () {
        session()->forget(['admin_id', 'admin_name']);
        return redirect()->route('admin.login');
    })->name('admin.logout');
    
    // Registro de nuevo administrador (acceso público)
    Route::get('register', function () {
        return view('admin.auth.register');
    })->name('admin.register');
    
    Route::post('register', function () {
        $data = request()->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:administradores,email',
            'password' => 'required|string|min:8|confirmed'
        ]);
        
        DB::table('administradores')->insert([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'contraseña' => password_hash($data['password'], PASSWORD_BCRYPT),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('admin.login')->with('success', 'Administrador registrado con éxito');
    })->name('admin.register.submit');
});

// Rutas protegidas (requieren autenticación)
Route::middleware(['admin.auth'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('dashboard', function () {
        $solicitudes = DB::table('peticiones')
                       ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
                       ->join('tipos_servicio', 'peticiones.tipo_servicio_id', '=', 'tipos_servicio.id')
                       ->leftJoin('ciudadanos', 'peticiones.ciudadano_id', '=', 'ciudadanos.id')
                       ->select('peticiones.*', 'comunidades.nombre as comunidad', 'tipos_servicio.nombre as servicio', 'ciudadanos.nombre as ciudadano')
                       ->get();
        
        $comunidades = DB::table('comunidades')->get();
        
        // Estadísticas para gráficos
        $stats = [
            'total' => DB::table('peticiones')->count(),
            'pendientes' => DB::table('peticiones')->where('estatus', 'pendiente')->count(),
            'proceso' => DB::table('peticiones')->where('estatus', 'en proceso')->count(),
            'resueltas' => DB::table('peticiones')->where('estatus', 'resuelto')->count(),
            'por_comunidad' => DB::table('peticiones')
                               ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
                               ->select('comunidades.nombre', DB::raw('count(*) as total'))
                               ->groupBy('comunidades.nombre')
                               ->get(),
            'por_mes' => DB::table('peticiones')
                         ->select(DB::raw('MONTH(created_at) as mes'), DB::raw('count(*) as total'))
                         ->groupBy(DB::raw('MONTH(created_at)'))
                         ->get()
        ];
        
        return view('admin.dashboard', compact('solicitudes', 'comunidades', 'stats'));
    })->name('admin.dashboard');
    
    // Generar PDF
    Route::get('reporte-pdf', function () {
        $filtros = request()->only('comunidad_id', 'mes');
        
        $query = DB::table('peticiones')
                 ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
                 ->join('tipos_servicio', 'peticiones.tipo_servicio_id', '=', 'tipos_servicio.id')
                 ->leftJoin('ciudadanos', 'peticiones.ciudadano_id', '=', 'ciudadanos.id')
                 ->select('peticiones.*', 'comunidades.nombre as comunidad', 'tipos_servicio.nombre as servicio', 'ciudadanos.nombre as ciudadano');
        
        if (!empty($filtros['comunidad_id'])) {
            $query->where('peticiones.comunidad_id', $filtros['comunidad_id']);
        }
        
        if (!empty($filtros['mes'])) {
            $query->whereMonth('peticiones.created_at', $filtros['mes']);
        }
        
        $solicitudes = $query->get();
        $comunidad = !empty($filtros['comunidad_id']) ? DB::table('comunidades')->find($filtros['comunidad_id'])->nombre : 'Todas';
        $mes = !empty($filtros['mes']) ? DateTime::createFromFormat('!m', $filtros['mes'])->format('F') : 'Todos';
        
        $pdf = PDF::loadView('admin.pdf.reporte', compact('solicitudes', 'comunidad', 'mes'));
        return $pdf->download('reporte-solicitudes.pdf');
    })->name('admin.reporte.pdf');
    
    // Servidores Públicos
    Route::prefix('servidores')->group(function () {
        Route::get('/', function () {
            $servidores = DB::table('servidores_publicos')
                          ->join('comunidades', 'servidores_publicos.comunidad_id', '=', 'comunidades.id')
                          ->join('direccion_departamento', 'servidores_publicos.departamento_id', '=', 'direccion_departamento.id')
                          ->select('servidores_publicos.*', 'comunidades.nombre as comunidad', 'direccion_departamento.nombre as departamento')
                          ->get();
            
            $comunidades = DB::table('comunidades')->get();
            $departamentos = DB::table('direccion_departamento')->get();
            
            return view('admin.servidores.index', compact('servidores', 'comunidades', 'departamentos'));
        })->name('admin.servidores.index');
        
        Route::post('/', function () {
            $data = request()->validate([
                'nombre' => 'required|string|max:255',
                'comunidad_id' => 'required|exists:comunidades,id',
                'departamento_id' => 'required|exists:direccion_departamento,id',
                'email' => 'required|email|unique:servidores_publicos,email',
                'password' => 'required|string|min:8',
                'telefono' => 'nullable|string|max:20'
            ]);
            
            DB::table('servidores_publicos')->insert([
                'nombre' => $data['nombre'],
                'comunidad_id' => $data['comunidad_id'],
                'departamento_id' => $data['departamento_id'],
                'email' => $data['email'],
                'contraseña' => password_hash($data['password'], PASSWORD_BCRYPT),
                'telefono' => $data['telefono'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return back()->with('success', 'Servidor público creado con éxito');
        })->name('admin.servidores.store');
        
        Route::get('/{id}/edit', function ($id) {
            $servidor = DB::table('servidores_publicos')->find($id);
            
            if (!$servidor) {
                return back()->with('error', 'Servidor no encontrado');
            }
            
            $comunidades = DB::table('comunidades')->get();
            $departamentos = DB::table('direccion_departamento')->get();
            
            return view('admin.servidores.edit', compact('servidor', 'comunidades', 'departamentos'));
        })->name('admin.servidores.edit');
        
        Route::put('/{id}', function ($id) {
            $data = request()->validate([
                'nombre' => 'required|string|max:255',
                'comunidad_id' => 'required|exists:comunidades,id',
                'departamento_id' => 'required|exists:direccion_departamento,id',
                'email' => 'required|email|unique:servidores_publicos,email,'.$id,
                'password' => 'nullable|string|min:8',
                'telefono' => 'nullable|string|max:20'
            ]);
            
            $updateData = [
                'nombre' => $data['nombre'],
                'comunidad_id' => $data['comunidad_id'],
                'departamento_id' => $data['departamento_id'],
                'email' => $data['email'],
                'telefono' => $data['telefono'] ?? null,
                'updated_at' => now()
            ];
            
            if (!empty($data['password'])) {
                $updateData['contraseña'] = password_hash($data['password'], PASSWORD_BCRYPT);
            }
            
            DB::table('servidores_publicos')->where('id', $id)->update($updateData);
            
            return back()->with('success', 'Servidor público actualizado con éxito');
        })->name('admin.servidores.update');
        
        Route::delete('/{id}', function ($id) {
            DB::table('servidores_publicos')->where('id', $id)->delete();
            return back()->with('success', 'Servidor público eliminado con éxito');
        })->name('admin.servidores.destroy');
    });
    
    // Ciudadanos
    Route::prefix('ciudadanos')->group(function () {
        Route::get('/', function () {
            $ciudadanos = DB::table('ciudadanos')->get();
            return view('admin.ciudadanos.index', compact('ciudadanos'));
        })->name('admin.ciudadanos.index');
        
        Route::post('/', function () {
            $data = request()->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'nullable|email|unique:ciudadanos,email',
                'telefono' => 'nullable|string|max:20'
            ]);
            
            DB::table('ciudadanos')->insert([
                'nombre' => $data['nombre'],
                'email' => $data['email'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return back()->with('success', 'Ciudadano creado con éxito');
        })->name('admin.ciudadanos.store');
        
        Route::get('/{id}/edit', function ($id) {
            $ciudadano = DB::table('ciudadanos')->find($id);
            
            if (!$ciudadano) {
                return back()->with('error', 'Ciudadano no encontrado');
            }
            
            return view('admin.ciudadanos.edit', compact('ciudadano'));
        })->name('admin.ciudadanos.edit');
        
        Route::put('/{id}', function ($id) {
            $data = request()->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'nullable|email|unique:ciudadanos,email,'.$id,
                'telefono' => 'nullable|string|max:20'
            ]);
            
            DB::table('ciudadanos')->where('id', $id)->update([
                'nombre' => $data['nombre'],
                'email' => $data['email'] ?? null,
                'telefono' => $data['telefono'] ?? null,
                'updated_at' => now()
            ]);
            
            return back()->with('success', 'Ciudadano actualizado con éxito');
        })->name('admin.ciudadanos.update');
        
        Route::delete('/{id}', function ($id) {
            DB::table('ciudadanos')->where('id', $id)->delete();
            return back()->with('success', 'Ciudadano eliminado con éxito');
        })->name('admin.ciudadanos.destroy');
    });
});


Route::get('reporte-pdf', function () {
    $filtros = request()->only('comunidad_id', 'mes', 'year');
    
    $query = DB::table('peticiones')
             ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
             ->join('tipos_servicio', 'peticiones.tipo_servicio_id', '=', 'tipos_servicio.id')
             ->leftJoin('ciudadanos', 'peticiones.ciudadano_id', '=', 'ciudadanos.id')
             ->select('peticiones.*', 'comunidades.nombre as comunidad', 'tipos_servicio.nombre as servicio', 'ciudadanos.nombre as ciudadano');
    
    if (!empty($filtros['comunidad_id'])) {
        $query->where('peticiones.comunidad_id', $filtros['comunidad_id']);
    }
    
    if (!empty($filtros['mes'])) {
        $query->whereMonth('peticiones.created_at', $filtros['mes']);
    }
    
    if (!empty($filtros['year'])) {
        $query->whereYear('peticiones.created_at', $filtros['year']);
    }
    
    $solicitudes = $query->get();
    $comunidad = !empty($filtros['comunidad_id']) ? DB::table('comunidades')->find($filtros['comunidad_id'])->nombre : 'Todas';
    
    $periodo = 'Todos los períodos';
    if (!empty($filtros['mes']) || !empty($filtros['year'])) {
        $periodo = '';
        if (!empty($filtros['mes'])) {
            $periodo .= DateTime::createFromFormat('!m', $filtros['mes'])->format('F');
        }
        if (!empty($filtros['year'])) {
            $periodo .= ' ' . $filtros['year'];
        }
    }
    
    $pdf = PDF::loadView('admin.pdf.reporte', compact('solicitudes', 'comunidad', 'periodo'));
    return $pdf->download('reporte-solicitudes.pdf');
})->name('admin.reporte.pdf');



Route::get('dashboard', function () {
    $filtros = request()->only('comunidad_id', 'mes', 'year');
    
    $query = DB::table('peticiones')
             ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
             ->join('tipos_servicio', 'peticiones.tipo_servicio_id', '=', 'tipos_servicio.id')
             ->leftJoin('ciudadanos', 'peticiones.ciudadano_id', '=', 'ciudadanos.id')
             ->select('peticiones.*', 'comunidades.nombre as comunidad', 'tipos_servicio.nombre as servicio', 'ciudadanos.nombre as ciudadano');
    
    if (!empty($filtros['comunidad_id'])) {
        $query->where('peticiones.comunidad_id', $filtros['comunidad_id']);
    }
    
    if (!empty($filtros['mes'])) {
        $query->whereMonth('peticiones.created_at', $filtros['mes']);
    }
    
    if (!empty($filtros['year'])) {
        $query->whereYear('peticiones.created_at', $filtros['year']);
    }
    
    $solicitudes = $query->get();
    $comunidades = DB::table('comunidades')->get();
    
    // Estadísticas para gráficos (deben usar los mismos filtros)
    $stats = [
        'total' => $query->count(),
        'pendientes' => $query->clone()->where('estatus', 'pendiente')->count(),
        'proceso' => $query->clone()->where('estatus', 'en proceso')->count(),
        'resueltas' => $query->clone()->where('estatus', 'resuelto')->count(),
        'por_comunidad' => DB::table('peticiones')
                           ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
                           ->select('comunidades.nombre', DB::raw('count(*) as total'))
                           ->when(!empty($filtros['mes']), function($q) use ($filtros) {
                               $q->whereMonth('peticiones.created_at', $filtros['mes']);
                           })
                           ->when(!empty($filtros['year']), function($q) use ($filtros) {
                               $q->whereYear('peticiones.created_at', $filtros['year']);
                           })
                           ->groupBy('comunidades.nombre')
                           ->get(),
        'por_mes' => DB::table('peticiones')
                     ->select(DB::raw('MONTH(created_at) as mes'), DB::raw('count(*) as total'))
                     ->when(!empty($filtros['comunidad_id']), function($q) use ($filtros) {
                         $q->where('comunidad_id', $filtros['comunidad_id']);
                     })
                     ->when(!empty($filtros['year']), function($q) use ($filtros) {
                         $q->whereYear('created_at', $filtros['year']);
                     })
                     ->groupBy(DB::raw('MONTH(created_at)'))
                     ->get()
    ];
    
    return view('admin.dashboard', compact('solicitudes', 'comunidades', 'stats', 'filtros'));
})->name('admin.dashboard');


// Rutas para el dashboard y solicitudes
Route::prefix('admin')->group(function() {
    // Dashboard principal
    Route::get('/dashboard', function() {
        // Obtener estadísticas
        $stats = [
            'total' => DB::table('peticiones')->count(),
            'pendientes' => DB::table('peticiones')->where('estatus', 'pendiente')->count(),
            'proceso' => DB::table('peticiones')->where('estatus', 'en proceso')->count(),
            'resueltas' => DB::table('peticiones')->where('estatus', 'resuelto')->count(),
            'por_comunidad' => DB::table('peticiones')
                ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
                ->select('comunidades.nombre', DB::raw('count(*) as total'))
                ->groupBy('comunidades.nombre')
                ->get(),
            'por_mes' => DB::table('peticiones')
                ->select(DB::raw('MONTH(created_at) as mes'), DB::raw('count(*) as total'))
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->get()
        ];

        // Obtener solicitudes con filtros
        $query = DB::table('peticiones')
            ->join('tipos_servicio', 'peticiones.tipo_servicio_id', '=', 'tipos_servicio.id')
            ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
            ->select('peticiones.*', 'tipos_servicio.nombre as servicio', 'comunidades.nombre as comunidad');

        if(request('comunidad_id')) {
            $query->where('peticiones.comunidad_id', request('comunidad_id'));
        }

        if(request('mes')) {
            $query->whereMonth('peticiones.created_at', request('mes'));
        }

        $solicitudes = $query->orderBy('peticiones.created_at', 'desc')->get();

        $comunidades = DB::table('comunidades')->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'solicitudes' => $solicitudes,
            'comunidades' => $comunidades
        ]);
    })->name('admin.dashboard');

    // Vista detalle de solicitud
    Route::get('/solicitudes/{id}', function($id) {
        $solicitud = DB::table('peticiones')
            ->join('tipos_servicio', 'peticiones.tipo_servicio_id', '=', 'tipos_servicio.id')
            ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
            ->select('peticiones.*', 'tipos_servicio.nombre as servicio', 'comunidades.nombre as comunidad')
            ->where('peticiones.id', $id)
            ->first();

        if (!$solicitud) {
            abort(404);
        }

        return view('admin.solicitudes.show', ['solicitud' => $solicitud]);
    })->name('admin.solicitudes.show');

    // Actualizar estatus de solicitud
    Route::put('/solicitudes/{id}', function(Request $request, $id) {
        $request->validate([
            'estatus' => 'required|in:pendiente,en proceso,resuelto'
        ]);

        DB::table('peticiones')
            ->where('id', $id)
            ->update(['estatus' => $request->estatus]);

        return back()->with('success', 'Estatus actualizado correctamente');
    })->name('admin.solicitudes.update');

    
});


/*
Route::get('/admin/dashboard', function () {
    $solicitudes = DB::table('peticiones')
                   ->select(
                       'peticiones.*',
                       DB::raw('DATE(peticiones.created_at) as fecha_creacion'),
                       'comunidades.nombre as comunidad',
                       'tipos_servicio.nombre as servicio',
                       'ciudadanos.nombre as ciudadano'
                   )
                   ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
                   ->join('tipos_servicio', 'peticiones.tipo_servicio_id', '=', 'tipos_servicio.id')
                   ->leftJoin('ciudadanos', 'peticiones.ciudadano_id', '=', 'ciudadanos.id')
                   ->get();

    $comunidades = DB::table('comunidades')->get();
    
    // Estadísticas para gráficos
    $stats = [
        'total' => DB::table('peticiones')->count(),
        'pendientes' => DB::table('peticiones')->where('estatus', 'pendiente')->count(),
        'proceso' => DB::table('peticiones')->where('estatus', 'en proceso')->count(),
        'resueltas' => DB::table('peticiones')->where('estatus', 'resuelto')->count(),
        'por_comunidad' => DB::table('peticiones')
                           ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
                           ->select('comunidades.nombre', DB::raw('count(*) as total'))
                           ->groupBy('comunidades.nombre')
                           ->get(),
        'por_mes' => DB::table('peticiones')
                     ->select(DB::raw('MONTH(created_at) as mes'), DB::raw('count(*) as total'))
                     ->groupBy(DB::raw('MONTH(created_at)'))
                     ->get()
    ];
    
    return view('admin.dashboard', compact('solicitudes', 'comunidades', 'stats'));
})->name('admin.dashboard');

*/
/*
// Rutas de autenticación 
Route::prefix('admin')->group(function () {
    // Login
    Route::get('login', function () {
        return view('admin.auth.login');
    })->name('admin.login');
    
    Route::post('login', function () {
        $credentials = request()->only('email', 'password');
        
        $admin = DB::table('administradores')
                 ->where('email', $credentials['email'])
                 ->first();
        
        if ($admin && password_verify($credentials['password'], $admin->contraseña)) {
            session(['admin_id' => $admin->id, 'admin_name' => $admin->nombre]);
            return redirect()->route('admin.dashboard');
        }
        
        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    })->name('admin.login.submit');
    
    // Logout
    Route::post('logout', function () {
        session()->forget(['admin_id', 'admin_name']);
        return redirect()->route('admin.login');
    })->name('admin.logout');
    
    // Registro de nuevo administrador (acceso público)
    Route::get('register', function () {
        return view('admin.auth.register');
    })->name('admin.register');
    
    Route::post('register', function () {
        $data = request()->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:administradores,email',
            'password' => 'required|string|min:8|confirmed'
        ]);
        
        DB::table('administradores')->insert([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'contraseña' => password_hash($data['password'], PASSWORD_BCRYPT),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('admin.login')->with('success', 'Administrador registrado con éxito');
    })->name('admin.register.submit');
});
*/

/*
// Rutas protegidas (requieren autenticación)
Route::middleware(['admin.auth'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('dashboard', function () {
        $solicitudes = DB::table('peticiones')
                       ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
                       ->join('tipos_servicio', 'peticiones.tipo_servicio_id', '=', 'tipos_servicio.id')
                       ->leftJoin('ciudadanos', 'peticiones.ciudadano_id', '=', 'ciudadanos.id')
                       ->select('peticiones.*', 'comunidades.nombre as comunidad', 'tipos_servicio.nombre as servicio', 'ciudadanos.nombre as ciudadano')
                       ->get();
        
        $comunidades = DB::table('comunidades')->get();
        
        // Estadísticas para gráficos
        $stats = [
            'total' => DB::table('peticiones')->count(),
            'pendientes' => DB::table('peticiones')->where('estatus', 'pendiente')->count(),
            'proceso' => DB::table('peticiones')->where('estatus', 'en proceso')->count(),
            'resueltas' => DB::table('peticiones')->where('estatus', 'resuelto')->count(),
            'por_comunidad' => DB::table('peticiones')
                               ->join('comunidades', 'peticiones.comunidad_id', '=', 'comunidades.id')
                               ->select('comunidades.nombre', DB::raw('count(*) as total'))
                               ->groupBy('comunidades.nombre')
                               ->get(),
            'por_mes' => DB::table('peticiones')
                         ->select(DB::raw('MONTH(created_at) as mes'), DB::raw('count(*) as total'))
                         ->groupBy(DB::raw('MONTH(created_at)'))
                         ->get()
        ];
        
        return view('admin.dashboard', compact('solicitudes', 'comunidades', 'stats'));
    })->name('admin.dashboard');
    

});
*/