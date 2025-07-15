<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ciudadano - Panel de Administración</title>
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
        
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            color: var(--wine-dark);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--wine-dark);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--wine-primary);
            box-shadow: 0 0 0 0.25rem rgba(114, 47, 55, 0.25);
        }
        
        .btn-primary {
            background-color: var(--wine-primary);
            border-color: var(--wine-primary);
            padding: 0.75rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--wine-dark);
            border-color: var(--wine-dark);
            transform: translateY(-2px);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-link {
            color: white;
            text-decoration: none;
            width: 100%;
            text-align: left;
            padding: 0.75rem 1.5rem !important;
            border-left: 4px solid transparent;
        }
        
        .btn-link:hover {
            background-color: var(--wine-secondary);
            border-left: 4px solid var(--wine-light);
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
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h4 class="m-0">Panel de Administración</h4>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.servidores.index') }}">
                        <i class="fas fa-users-cog"></i> <span>Servidores Públicos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ciudadanos.index') }}" class="active">
                        <i class="fas fa-users"></i> <span>Ciudadanos</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link">
                            <i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
                <div class="container-fluid">
                    <span class="navbar-brand">Bienvenido, {{ session('admin_name') }}</span>
                </div>
            </nav>
            
            <!-- Content -->
            <div class="container-fluid p-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0"><i class="fas fa-user-edit me-2"></i>Editar Ciudadano</h5>
                        <a href="{{ route('admin.ciudadanos.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                    </div>
                    
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.ciudadanos.update', $ciudadano->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="{{ old('nombre', $ciudadano->nombre) }}" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email', $ciudadano->email ?? '') }}">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" id="telefono" name="telefono" 
                                           value="{{ old('telefono', $ciudadano->telefono ?? '') }}">
                                </div>
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary py-2">
                                    <i class="fas fa-save me-2"></i>Actualizar Ciudadano
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>