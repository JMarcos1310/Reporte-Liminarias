<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ciudadanos - Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --wine-primary: #722f37;
            --wine-secondary: #8a3a44;
            --wine-light: #f8f2f3;
            --wine-dark: #5a232a;
            --wine-darker: #3d1a1f;
            --wine-accent: #e4b7b2;
        }

        body {
            background: linear-gradient(120deg, #f9f9f9 60%, #f8f2f3 100%);
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, var(--wine-dark), var(--wine-darker));
            color: white;
            height: 100vh;
            position: fixed;
            box-shadow: 2px 0 10px rgba(90,35,42,0.08);
            z-index: 100;
            transition: all 0.3s;
        }

        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            border-bottom: 1px solid var(--wine-secondary);
            text-align: center;
            background: var(--wine-darker);
            letter-spacing: 1px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin-top: 1rem;
        }

        .sidebar-menu li a,
        .btn-link {
            display: flex;
            align-items: center;
            padding: 0.85rem 1.5rem;
            color: white;
            text-decoration: none;
            border-left: 4px solid transparent;
            border-radius: 0 2rem 2rem 0;
            font-weight: 500;
            transition: background 0.2s, border-color 0.2s, color 0.2s;
        }

        .sidebar-menu li a:hover,
        .btn-link:hover {
            background: var(--wine-secondary);
            border-left: 4px solid var(--wine-accent);
            color: var(--wine-accent);
        }

        .sidebar-menu li a.active {
            background: var(--wine-secondary);
            border-left: 4px solid var(--wine-accent);
            color: var(--wine-accent);
        }

        .sidebar-menu li a i,
        .btn-link i {
            margin-right: 1rem;
            width: 22px;
            text-align: center;
            font-size: 1.1rem;
        }

        .navbar-custom {
            background: var(--wine-primary);
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            border-radius: 0 0 0.75rem 0.75rem;
        }

        .navbar-brand {
            font-weight: 600;
            letter-spacing: 1px;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(90,47,55,0.07);
            overflow: hidden;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 1.5rem 2rem;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--wine-primary), var(--wine-secondary));
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(114,47,55,0.08);
            transition: background 0.2s, transform 0.2s;
        }

        .btn-primary:hover, .btn-primary:focus {
            background: var(--wine-dark);
            color: #fff;
            transform: translateY(-2px) scale(1.03);
        }

        .btn-warning {
            background: #ffc107;
            border: none;
            color: #5a232a;
            font-weight: 500;
            border-radius: 0.5rem;
        }

        .btn-danger {
            background: #dc3545;
            border: none;
            color: #fff;
            font-weight: 500;
            border-radius: 0.5rem;
        }

        .table {
            border-radius: 0.75rem;
            overflow: hidden;
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--wine-light);
            color: var(--wine-dark);
            border-bottom: 2px solid var(--wine-primary);
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .table tbody tr:hover {
            background: rgba(114, 47, 55, 0.08);
        }

        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #e4b7b2;
            transition: border 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--wine-primary);
            box-shadow: 0 0 0 2px #e4b7b2;
        }

        .modal-content {
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(90,47,55,0.13);
        }

        .modal-header {
            border-bottom: 1px solid rgba(0,0,0,0.08);
            background: var(--wine-light);
            border-radius: 1rem 1rem 0 0;
        }

        .modal-footer {
            border-top: 1px solid rgba(0,0,0,0.08);
            background: #fff;
            border-radius: 0 0 1rem 1rem;
        }

        .alert-success {
            background: #e4f7ea;
            color: #256029;
            border: 1px solid #b7e4c7;
            border-radius: 0.5rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            .sidebar-header h4, .sidebar-menu li a span, .btn-link span {
                display: none;
            }
            .sidebar-menu li a, .btn-link {
                justify-content: center;
                padding: 0.85rem 0;
            }
            .sidebar-menu li a i, .btn-link i {
                margin-right: 0;
                font-size: 1.3rem;
            }
            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
            .card-header, .card-body {
                padding: 1rem;
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
                        <h5 class="m-0"><i class="fas fa-users me-2"></i>Listado de Ciudadanos</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearCiudadanoModal">
                            <i class="fas fa-plus me-1"></i> Nuevo Ciudadano
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
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ciudadanos as $ciudadano)
                                        <tr>
                                            <td>{{ $ciudadano->nombre }}</td>
                                            <td>{{ $ciudadano->email ?? 'N/A' }}</td>
                                            <td>{{ $ciudadano->telefono ?? 'N/A' }}</td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.ciudadanos.edit', $ciudadano->id) }}" 
                                                       class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.ciudadanos.destroy', $ciudadano->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('¿Estás seguro de eliminar este ciudadano?')"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="fas fa-users-slash fa-2x mb-3" style="color: var(--wine-light);"></i>
                                                <p class="mb-0">No hay ciudadanos registrados</p>
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

    <!-- Modal para crear nuevo ciudadano -->
    <div class="modal fade" id="crearCiudadanoModal" tabindex="-1" aria-labelledby="crearCiudadanoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearCiudadanoModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Ciudadano
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="{{ route('admin.ciudadanos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
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
</body>
</html>