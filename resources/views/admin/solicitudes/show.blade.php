<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Solicitud - Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --wine-primary: #722f37;
            --wine-secondary: #8a3a44;
            --wine-light: #f8f2f3;
            --wine-dark: #5a232a;
            --wine-darker: #3d1a1f;
        }
        
        body {
            background-color: #f9f9f9;
            padding-top: 100px;
        }
        
        .sidebar {
            width: 250px;
            background-color: var(--wine-dark);
            color: white;
            height: 100vh;
            position: fixed;
            transition: all 0.3s;
        }
        
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--wine-secondary);
            text-align: center;
            background-color: var(--wine-darker);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        
        .sidebar-menu li a:hover {
            background-color: var(--wine-secondary);
            border-left: 4px solid var(--wine-light);
        }
        
        .sidebar-menu li a.active {
            background-color: var(--wine-secondary);
            border-left: 4px solid var(--wine-light);
        }
        
        .sidebar-menu li a i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .navbar-custom {
            background-color: var(--wine-primary);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card-dashboard {
            color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .card-dashboard:hover {
            transform: translateY(-5px);
        }
        
        .card-dashboard.bg-primary {
            background-color: var(--wine-primary) !important;
        }
        
        .card-dashboard.bg-warning {
            background-color: #8a3a44 !important;
        }
        
        .card-dashboard.bg-info {
            background-color: #6d2e36 !important;
        }
        
        .card-dashboard.bg-success {
            background-color: #5a232a !important;
        }
        
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(114, 47, 55, 0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
            color: var(--wine-dark);
        }
        
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: var(--wine-light);
            color: var(--wine-dark);
            border-bottom: 2px solid var(--wine-primary);
        }
        
        .table tbody tr:hover {
            background-color: rgba(114, 47, 55, 0.05);
        }
        
        .badge.bg-warning {
            background-color: var(--wine-secondary) !important;
            color: white;
        }
        
        .badge.bg-info {
            background-color: var(--wine-dark) !important;
            color: white;
        }
        
        .badge.bg-success {
            background-color: var(--wine-darker) !important;
            color: white;
        }
        
        .btn-primary {
            background-color: var(--wine-primary);
            border-color: var(--wine-primary);
        }
        
        .btn-primary:hover {
            background-color: var(--wine-dark);
            border-color: var(--wine-dark);
        }
        
        .btn-danger {
            background-color: #8a3a44;
            border-color: #8a3a44;
        }
        
        .btn-danger:hover {
            background-color: #722f37;
            border-color: #722f37;
        }
        
        .form-select:focus, .form-control:focus {
            border-color: var(--wine-primary);
            box-shadow: 0 0 0 0.25rem rgba(114, 47, 55, 0.25);
        }
        
        .select2-container--default .select2-selection--single {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            height: 38px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        
        .chart-container {
            height: 400px;
            margin-bottom: 2rem;
        }
        
        .filters-container {
            background-color: var(--wine-light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            float: right;
            width: 350px;
            margin-left: 20px;
        }
        
        .navbar-wine {
            background-color: #722f37;
            height: 110px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1050;
        }

        .content-with-filter {
            display: flex;
            flex-wrap: wrap;
        }
        
        .main-content-area {
            flex: 1;
            min-width: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar-header h4, .sidebar-menu li a span {
                display: none;
            }
            
            .sidebar-menu li a {
                text-align: center;
                padding: 0.75rem 0;
            }
            
            .sidebar-menu li a i {
                margin-right: 0;
                font-size: 1.25rem;
            }
            
            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
            
            .chart-container {
                height: 300px;
            }
            
            .filters-container {
                float: none;
                width: 100%;
                margin-left: 0;
            }
        }

        /* Barra de navegación superior personalizada */
        .navbar-wine {
            background-color: #722f37; /* Color de fondo */
            height: 110px; /* Altura fija */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Sombra */
            z-index: 1050; /* Z-index alto para superposición */
        }

        /* Contenedor flexible para contenido con filtros */
        .content-with-filter {
            display: flex; /* Diseño flexible */
            flex-wrap: wrap; /* Permite envolver elementos */
        }
        
        /* Área de contenido principal */
        .main-content-area {
            flex: 1; /* Ocupa espacio disponible */
            min-width: 0; /* Solución para problemas de flexbox */
        }

    </style>
</head>
<body>
    
    <nav class="navbar navbar-wine fixed-top">
        <div class="container-fluid">
            <!-- Logo izquierdo -->
            <a class="navbar-brand ms-4" href="#">
                <img src="imagenes/logo.png" alt="Logo Izquierdo" height="90px">
            </a>
    
            <!-- Título centrado -->
            <div class="position-absolute top-50 start-50 translate-middle" style= color:white>
                <h4 style="font-family:'Times-new-roman" class="m-0 fw-bold">Panel de Administración</h4>
            </div>
    
            <!-- Logo derecho -->
            <a class="navbar-brand ms-auto" href="#" style="margin-left: 10px;">
                <img src="imagenes/logoh.png" alt="Logo Derecho" height="90">
            </a>
        </div>
    </nav>

    <div class="d-flex">

        <div class="sidebar">
            <div class="sidebar-header"></div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.servidores.index') }}">
                        <i class="fas fa-users-cog"></i> Servidores Públicos
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ciudadanos.index') }}">
                        <i class="fas fa-users"></i> Ciudadanos
                    </a>
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start ps-3 py-2">
                            <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        
        
        <div class="main-content">
            
            <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
                <div class="container-fluid">
                    <span class="navbar-brand">Bienvenido: {{ session('admin_name') }}</span>
                </div>
            </nav>
            
            
            <div class="container-fluid p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="row mb-4">
                    <div class="col-12">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3">
                            <i class="fas fa-arrow-left me-2"></i> Volver al Dashboard
                        </a>
                        <h2>Solicitud #{{ $solicitud->numero_solicitud }}</h2>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Información General</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Servicio:</div>
                                    <div class="col-md-8">{{ $solicitud->servicio }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Comunidad:</div>
                                    <div class="col-md-8">{{ $solicitud->comunidad }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Dirección:</div>
                                    <div class="col-md-8">{{ $solicitud->direccion }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Fecha:</div>
                                    <div class="col-md-8">{{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Estatus:</div>
                                    <div class="col-md-8">
                                        @if($solicitud->estatus == 'pendiente')
                                            <span class="badge bg-warning">Pendiente</span>
                                        @elseif($solicitud->estatus == 'en proceso')
                                            <span class="badge bg-info">En Proceso</span>
                                        @else
                                            <span class="badge bg-success">Resuelto</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Información del Ciudadano</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Nombre:</div>
                                    <div class="col-md-8">{{ $solicitud->nombre ?? 'Anónimo' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Email:</div>
                                    <div class="col-md-8">{{ $solicitud->email ?? 'No proporcionado' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Teléfono:</div>
                                    <div class="col-md-8">{{ $solicitud->telefono ?? 'No proporcionado' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Observaciones</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $solicitud->observaciones ?? 'No hay observaciones registradas' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Actualizar Estatus</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.solicitudes.update', $solicitud->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="row">
                                        <div class="col-md-8">
                                            <select name="estatus" class="form-select">
                                                <option value="pendiente" {{ $solicitud->estatus == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="en proceso" {{ $solicitud->estatus == 'en proceso' ? 'selected' : '' }}>En Proceso</option>
                                                <option value="resuelto" {{ $solicitud->estatus == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-save me-2"></i> Actualizar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>