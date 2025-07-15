<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servidor - Panel de Administración</title>
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

        .sidebar-menu li a:hover,
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

        .form-control, .select2-selection {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: all 0.3s;
            height: 45px;
            display: flex;
            align-items: center;
        }

        .form-control:focus, .select2-selection:focus {
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

        /* ✅ Estilo mejorado para igualar selects a los inputs */
        .select2-container--default .select2-selection--single {
            height: 45px !important;
            border: 1px solid #ced4da !important;
            border-radius: 6px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5 !important;
            padding-left: 12px !important;
            padding-right: 30px !important;
            width: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 43px !important;
            right: 8px !important;
        }

        /* Asegurar que el contenedor de Select2 tenga el mismo ancho */
        .select2-container {
            width: 100% !important;
        }

        /* Ajustar el tema de Select2 para Bootstrap 5 */
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            transition: all 0.3s;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--wine-primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(114, 47, 55, 0.25) !important;
        }

        /* Ajustar el dropdown */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--wine-primary);
        }

        /* Eliminar el padding adicional en las columnas */
        .col-md-6 {
            padding-right: 12px;
            padding-left: 12px;
        }

        /* Ajustar el input-group para que coincida con los selects */
        .input-group-text {
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            background-color: #f8f9fa;
        }

        .input-group .form-control {
            display: flex;
            align-items: center;
        }

        /* Centrar texto en selects */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            display: flex;
            align-items: center;
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
            <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <li><a href="{{ route('admin.servidores.index') }}" class="active"><i class="fas fa-users-cog"></i> <span>Servidores</span></a></li>
            <li><a href="{{ route('admin.ciudadanos.index') }}"><i class="fas fa-users"></i> <span>Ciudadanos</span></a></li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST">@csrf
                    <button type="submit" class="btn btn-link"><i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span></button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
            <div class="container-fluid">
                <span class="navbar-brand">Bienvenido, {{ session('admin_name') }}</span>
            </div>
        </nav>

        <div class="container-fluid p-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0"><i class="fas fa-user-edit me-2"></i>Editar Servidor Público</h5>
                    <a href="{{ route('admin.servidores.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Volver</a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.servidores.update', $servidor->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $servidor->nombre) }}" required style="display: flex; align-items: center;">
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $servidor->email) }}" required style="display: flex; align-items: center;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="comunidad_id" class="form-label">Comunidad</label>
                                <select class="form-control" id="comunidad_id" name="comunidad_id" required style="height: 45px; width: 100%;">
                                    @foreach($comunidades as $comunidad)
                                        <option value="{{ $comunidad->id }}" {{ old('comunidad_id', $servidor->comunidad_id) == $comunidad->id ? 'selected' : '' }}>{{ $comunidad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="departamento_id" class="form-label">Departamento</label>
                                <select class="form-control" id="departamento_id" name="departamento_id" required style="height: 45px; width: 100%;">
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}" {{ old('departamento_id', $servidor->departamento_id) == $departamento->id ? 'selected' : '' }}>{{ $departamento->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $servidor->telefono ?? '') }}" style="display: flex; align-items: center;">
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Dejar en blanco para no cambiar" style="display: flex; align-items: center;">
                                    <!-- ✅ Botón para mostrar/ocultar contraseña -->
                                    <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </span>
                                </div>
                                <small class="text-muted">Mínimo 8 caracteres</small>
                            </div>
                        </div>

                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-save me-2"></i>Actualizar Servidor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#comunidad_id, #departamento_id').select2({
            width: '100%',
            minimumResultsForSearch: Infinity,
            dropdownParent: $('.card-body')
        });
    });

    // ✅ Función para mostrar/ocultar contraseña
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
</body>
</html>