<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servidores Públicos - Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
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
        }
        
        .btn-primary {
            background-color: var(--wine-primary);
            border-color: var(--wine-primary);
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
        
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
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
        
        .modal-header {
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .modal-footer {
            border-top: 1px solid rgba(0,0,0,0.1);
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
                    <a href="{{ route('admin.servidores.index') }}" class="active">
                        <i class="fas fa-users-cog"></i> <span>Servidores</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.ciudadanos.index') }}">
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
                        <h5 class="m-0"><i class="fas fa-users-cog me-2"></i>Listado de Servidores Públicos</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearServidorModal">
                            <i class="fas fa-plus me-1"></i> Nuevo Servidor
                        </button>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success mb-4">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Comunidad</th>
                                        <th>Departamento</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($servidores as $servidor)
                                        <tr>
                                            <td>{{ $servidor->nombre }}</td>
                                            <td>{{ $servidor->comunidad }}</td>
                                            <td>{{ $servidor->departamento }}</td>
                                            <td>{{ $servidor->email }}</td>
                                            <td>{{ $servidor->telefono ?? 'N/A' }}</td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.servidores.edit', $servidor->id) }}" 
                                                       class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.servidores.destroy', $servidor->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('¿Estás seguro de eliminar este servidor?')"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-user-slash fa-2x mb-3" style="color: var(--wine-light);"></i>
                                                <p class="mb-0">No hay servidores públicos registrados</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear nuevo servidor -->
    <div class="modal fade" id="crearServidorModal" tabindex="-1" aria-labelledby="crearServidorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearServidorModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Servidor Público
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="{{ route('admin.servidores.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="comunidad_id" class="form-label">Comunidad</label>
                                <select class="form-select" id="comunidad_id" name="comunidad_id" required>
                                    <option value="">Seleccionar comunidad</option>
                                    @foreach($comunidades as $comunidad)
                                        <option value="{{ $comunidad->id }}">{{ $comunidad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="departamento_id" class="form-label">Departamento</label>
                                <select class="form-select" id="departamento_id" name="departamento_id" required>
                                    <option value="">Seleccionar departamento</option>
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="text-muted">Mínimo 8 caracteres</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono">
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#comunidad_id, #departamento_id').select2({
                width: '100%',
                dropdownParent: $('#crearServidorModal'),
                theme: 'bootstrap-5'
            });
        });
    </script>
</body>
</html>