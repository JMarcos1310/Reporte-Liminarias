<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8"> <!-- Codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
    <title>Dashboard - Panel de Administración</title> <!-- Título de la página -->
    
    <!-- Enlaces a hojas de estilo y librerías externas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"> <!-- Select2 CSS -->
    
    <style>
        /* Variables CSS personalizadas para los colores del tema */
        :root {
            --wine-primary: #722f37; /* Color principal (vino) */
            --wine-secondary: #8a3a44; /* Color secundario */
            --wine-light: #f8f2f3; /* Color claro */
            --wine-dark: #5a232a; /* Color oscuro */
            --wine-darker: #3d1a1f; /* Color más oscuro */
        }
        
        /* Estilos generales del cuerpo */
        body {
            background-color: #f9f9f9; /* Fondo gris claro */
            padding-top: 100px; /* Espacio para la barra de navegación fija */
        }
        
        /* Barra lateral (sidebar) */
        .sidebar {
            width: 250px; /* Ancho fijo */
            background-color: var(--wine-dark); /* Color de fondo */
            color: white; /* Color de texto */
            height: 100vh; /* Altura completa de la ventana */
            position: fixed; /* Posición fija */
            transition: all 0.3s; /* Transición suave para animaciones */
        }
        
        /* Contenido principal */
        .main-content {
            margin-left: 250px; /* Margen para no solapar con el sidebar */
            width: calc(100% - 250px); /* Ancho calculado */
            transition: all 0.3s; /* Transición suave */
        }
        
        /* Encabezado del sidebar */
        .sidebar-header {
            padding: 1.5rem; /* Espaciado interno */
            border-bottom: 1px solid var(--wine-secondary); /* Borde inferior */
            text-align: center; /* Texto centrado */
            background-color: var(--wine-darker); /* Color de fondo */
        }
        
        /* Menú del sidebar */
        .sidebar-menu {
            list-style: none; /* Elimina viñetas de lista */
            padding: 0; /* Elimina padding por defecto */
        }
        
        /* Elementos del menú */
        .sidebar-menu li a {
            display: block; /* Hace que el enlace ocupe todo el ancho */
            padding: 0.75rem 1.5rem; /* Espaciado interno */
            color: white; /* Color de texto */
            text-decoration: none; /* Sin subrayado */
            transition: all 0.3s; /* Transición suave */
            border-left: 4px solid transparent; /* Borde izquierdo transparente */
        }
        
        /* Efecto hover en elementos del menú */
        .sidebar-menu li a:hover {
            background-color: var(--wine-secondary); /* Cambio de color */
            border-left: 4px solid var(--wine-light); /* Borde izquierdo visible */
        }
        
        /* Elemento activo del menú */
        .sidebar-menu li a.active {
            background-color: var(--wine-secondary); /* Color de fondo */
            border-left: 4px solid var(--wine-light); /* Borde izquierdo */
        }
        
        /* Iconos del menú */
        .sidebar-menu li a i {
            margin-right: 0.75rem; /* Espacio a la derecha */
            width: 20px; /* Ancho fijo */
            text-align: center; /* Centrado de icono */
        }
        
        /* Barra de navegación superior personalizada */
        .navbar-custom {
            background-color: var(--wine-primary); /* Color de fondo */
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); /* Sombra */
        }
        
        /* Tarjetas del dashboard */
        .card-dashboard {
            color: white; /* Color de texto */
            border-radius: 0.5rem; /* Bordes redondeados */
            padding: 1.5rem; /* Espaciado interno */
            margin-bottom: 1rem; /* Margen inferior */
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); /* Sombra */
            transition: transform 0.3s; /* Transición para efecto hover */
        }
        
        /* Efecto hover en tarjetas */
        .card-dashboard:hover {
            transform: translateY(-5px); /* Movimiento hacia arriba */
        }
        
        /* Variantes de colores para las tarjetas */
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
        
        /* Estilos generales para tarjetas */
        .card {
            border: none; /* Sin borde */
            border-radius: 0.5rem; /* Bordes redondeados */
            box-shadow: 0 2px 15px rgba(0,0,0,0.05); /* Sombra suave */
            border: 1px solid rgba(114, 47, 55, 0.1); /* Borde sutil */
        }
        
        /* Encabezado de tarjetas */
        .card-header {
            background-color: white; /* Fondo blanco */
            border-bottom: 1px solid rgba(0,0,0,0.05); /* Borde inferior sutil */
            padding: 1.25rem 1.5rem; /* Espaciado interno */
            color: var(--wine-dark); /* Color de texto */
        }
        
        /* Estilos para tablas */
        .table {
            border-radius: 0.5rem; /* Bordes redondeados */
            overflow: hidden; /* Oculta contenido que sobresale */
        }
        
        /* Encabezado de tabla */
        .table thead th {
            background-color: var(--wine-light); /* Color de fondo */
            color: var(--wine-dark); /* Color de texto */
            border-bottom: 2px solid var(--wine-primary); /* Borde inferior */
        }
        
        /* Efecto hover en filas de tabla */
        .table tbody tr:hover {
            background-color: rgba(114, 47, 55, 0.05); /* Fondo sutil al pasar mouse */
        }
        
        /* Estilos para badges (etiquetas) */
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
        
        /* Botones primarios */
        .btn-primary {
            background-color: var(--wine-primary); /* Color de fondo */
            border-color: var(--wine-primary); /* Color de borde */
        }
        
        .btn-primary:hover {
            background-color: var(--wine-dark); /* Color hover */
            border-color: var(--wine-dark); /* Borde hover */
        }
        
        /* Botones de peligro */
        .btn-danger {
            background-color: #8a3a44; /* Color de fondo */
            border-color: #8a3a44; /* Color de borde */
        }
        
        .btn-danger:hover {
            background-color: #722f37; /* Color hover */
            border-color: #722f37; /* Borde hover */
        }
        
        /* Estilos para inputs y selects enfocados */
        .form-select:focus, .form-control:focus {
            border-color: var(--wine-primary); /* Color de borde */
            box-shadow: 0 0 0 0.25rem rgba(114, 47, 55, 0.25); /* Sombra */
        }
        
        /* Estilos para Select2 */
        .select2-container--default .select2-selection--single {
            border: 1px solid #ced4da; /* Borde similar a Bootstrap */
            border-radius: 0.25rem; /* Bordes redondeados */
            height: 38px; /* Altura consistente */
        }
        
        /* Contenedor para gráficos */
        .chart-container {
            height: 400px; /* Altura fija */
            margin-bottom: 2rem; /* Margen inferior */
        }
        
        /* Contenedor de filtros */
        .filters-container {
            background-color: var(--wine-light); /* Color de fondo */
            padding: 1.5rem; /* Espaciado interno */
            border-radius: 0.5rem; /* Bordes redondeados */
            margin-bottom: 1.5rem; /* Margen inferior */
            float: right; /* Flotado a la derecha */
            width: 350px; /* Ancho fijo */
            margin-left: 20px; /* Margen izquierdo */
        }
        
        /* Media queries para diseño responsive */
        @media (max-width: 768px) {
            /* Sidebar en móviles */
            .sidebar {
                width: 70px; /* Ancho reducido */
                overflow: hidden; /* Oculta texto que sobresale */
            }
            
            /* Oculta texto en móviles */
            .sidebar-header h4, .sidebar-menu li a span {
                display: none;
            }
            
            /* Ajusta elementos del menú */
            .sidebar-menu li a {
                text-align: center; /* Centrado */
                padding: 0.75rem 0; /* Espaciado reducido */
            }
            
            /* Ajusta iconos */
            .sidebar-menu li a i {
                margin-right: 0; /* Sin margen */
                font-size: 1.25rem; /* Tamaño aumentado */
            }
            
            /* Ajusta contenido principal */
            .main-content {
                margin-left: 70px; /* Margen reducido */
                width: calc(100% - 70px); /* Ancho recalculado */
            }
            
            /* Ajusta altura de gráficos */
            .chart-container {
                height: 300px; /* Altura reducida */
            }
            
            /* Ajusta contenedor de filtros */
            .filters-container {
                float: none; /* Sin flotado */
                width: 100%; /* Ancho completo */
                margin-left: 0; /* Sin margen izquierdo */
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

<!-- Barra de navegación superior -->
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

<!-- Estructura principal de la página -->
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <!-- Encabezado del sidebar (vacío en este caso) -->
        </div>
        
        <!-- Menú del sidebar -->
        <ul class="sidebar-menu">
            <!-- Enlace al dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <!-- Enlace a servidores públicos -->
            <li>
                <a href="{{ route('admin.servidores.index') }}" class="{{ request()->routeIs('admin.servidores.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i> Servidores Públicos
                </a>
            </li>
            <!-- Enlace a ciudadanos -->
            <li>
                <a href="{{ route('admin.ciudadanos.index') }}" class="{{ request()->routeIs('admin.ciudadanos.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Ciudadanos
                </a>
            </li>
            <!-- Formulario de cierre de sesión -->
            <li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf <!-- Token CSRF para protección -->
                    <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start ps-3 py-2">
                        <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                    </button>
                </form>
            </li>
        </ul>
    </div>
    
    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Barra de navegación secundaria -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
            <div class="container-fluid">
                <!-- Mensaje de bienvenida con nombre de usuario -->
                <span class="navbar-brand">Bienvenido: {{ session('admin_name') }}</span>
            </div>
        </nav>
        
        <!-- Contenedor principal del contenido -->
        <div class="container-fluid p-4">
            <!-- Fila de tarjetas de resumen -->
            <div class="row mb-4">
                <!-- Tarjeta de total de solicitudes -->
                <div class="col-md-3">
                    <div class="card-dashboard bg-primary">
                        <h5 class="card-title">Total Solicitudes</h5>
                        <h2 class="card-text">{{ $stats['total'] }}</h2>
                    </div>
                </div>
                
                <!-- Tarjeta de solicitudes pendientes -->
                <div class="col-md-3">
                    <div class="card-dashboard bg-warning text-white">
                        <h5 class="card-title">Pendientes</h5>
                        <h2 class="card-text">{{ $stats['pendientes'] }}</h2>
                    </div>
                </div>
                
                <!-- Tarjeta de solicitudes en proceso -->
                <div class="col-md-3">
                    <div class="card-dashboard bg-info text-white">
                        <h5 class="card-title">En Proceso</h5>
                        <h2 class="card-text">{{ $stats['proceso'] }}</h2>
                    </div>
                </div>
                
                <!-- Tarjeta de solicitudes resueltas -->
                <div class="col-md-3">
                    <div class="card-dashboard bg-success text-white">
                        <h5 class="card-title">Resueltas</h5>
                        <h2 class="card-text">{{ $stats['resueltas'] }}</h2>
                    </div>
                </div>
            </div>
            
            <!-- Contenedor flexible para contenido y filtros -->
            <div class="content-with-filter">
                <!-- Área principal de contenido -->
                <div class="main-content-area">
                    <!-- Tarjeta con tabla de solicitudes -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th># Solicitud</th>
                                            <th>Servicio</th>
                                            <th>Comunidad</th>
                                            <th>Ciudadano</th>
                                            <th>Dirección</th>
                                            <th>Estatus</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <!-- Loop a través de las solicitudes -->
                                        @forelse($solicitudes as $solicitud)
                                            <!-- Fila clickeable que redirige al detalle -->
                                            <tr onclick="window.location='{{ url('/admin/solicitudes/' . $solicitud->id) }}'" style="cursor: pointer;">
                                                <td>{{ $solicitud->numero_solicitud }}</td>
                                                <td>{{ $solicitud->servicio }}</td>
                                                <td>{{ $solicitud->comunidad }}</td>
                                                <td>{{ $solicitud->ciudadano ??'Anonimo'}}</td>
                                                <td>{{ $solicitud->direccion }}</td>
                                                <td>
                                                    <!-- Muestra el estatus con un badge de color -->
                                                    @if($solicitud->estatus == 'pendiente')
                                                        <span class="badge bg-warning">Pendiente</span>
                                                    @elseif($solicitud->estatus == 'en proceso')
                                                        <span class="badge bg-info">En Proceso</span>
                                                    @else
                                                        <span class="badge bg-success">Resuelto</span>
                                                    @endif
                                                </td>
                                                <td>{{ \carbon\carbon::parse($solicitud->created_at)->format ('d/m/Y') }}</td>
                                            </tr>
                                        @empty
                                            <!-- Mensaje cuando no hay solicitudes -->
                                            <tr>
                                                <td colspan="7" class="text-center">No hay solicitudes registradas</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tarjeta con gráfico de solicitudes por comunidad -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Solicitudes por Comunidad</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="comunidadChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tarjeta con gráfico de solicitudes por mes -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Solicitudes por Mes</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="mesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contenedor de filtros -->
                <div class="filters-container">
                    <h5 class="mb-3">Generar Reporte</h5>
                    <!-- Formulario de filtros -->
                    <form action="{{ route('admin.dashboard') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <!-- Selector de comunidad -->
                            <div class="col-md-12">
                                <label for="comunidad_id" class="form-label">Comunidad</label>
                                <select name="comunidad_id" id="comunidad_id" class="form-select">
                                    <option value="">Todas las comunidades</option>
                                    <!-- Loop a través de comunidades disponibles -->
                                    @foreach($comunidades as $comunidad)
                                        <option value="{{ $comunidad->id }}" {{ request('comunidad_id') == $comunidad->id ? 'selected' : '' }}>
                                            {{ $comunidad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Selector de mes -->
                            <div class="col-md-12">
                                <label for="mes" class="form-label">Mes</label>
                                <select name="mes" id="mes" class="form-select">
                                    <option value="">Todos los meses</option>
                                    <!-- Loop para los 12 meses del año -->
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('mes') == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            
                            <!-- Botón de filtrar -->
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i> Filtrar
                                </button>
                            </div>
                            
                            <!-- Botón para generar PDF -->
                            <div class="col-md-12">
                                <a href="{{ route('admin.reporte.pdf') }}?comunidad_id={{ request('comunidad_id') }}&mes={{ request('mes') }}" 
                                   class="btn btn-danger w-100" target="_blank">
                                    <i class="fas fa-file-pdf me-1"></i> Generar PDF
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts necesarios -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script> <!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> <!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js para gráficos -->

<script>
    // Inicialización de Select2 para los selects
    $(document).ready(function() {
        $('select').select2({
            width: '100%' // Asegura que ocupe todo el ancho disponible
        });
    });

    // Configuración del gráfico de barras por comunidad
    const comunidadCtx = document.getElementById('comunidadChart').getContext('2d');
    const comunidadChart = new Chart(comunidadCtx, {
        type: 'bar', // Tipo de gráfico
        data: {
            labels: {!! json_encode($stats['por_comunidad']->pluck('nombre')) !!}, // Nombres de comunidades
            datasets: [{
                label: 'Solicitudes por Comunidad', // Etiqueta del dataset
                data: {!! json_encode($stats['por_comunidad']->pluck('total')) !!}, // Datos de cantidad
                backgroundColor: [ // Colores de fondo
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [ // Colores de borde
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1 // Ancho del borde
            }]
        },
        options: {
            responsive: true, // Gráfico responsive
            maintainAspectRatio: false, // No mantener relación de aspecto
            scales: {
                y: {
                    beginAtZero: true // Eje Y comienza en 0
                }
            }
        }
    });
    
    // Configuración para el gráfico de líneas por mes
    const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic']; // Nombres cortos de meses
    const datosMeses = Array(12).fill(0); // Inicializa array con 12 ceros
    
    // Llena el array con los datos reales
    @foreach($stats['por_mes'] as $mesData)
        datosMeses[{{ $mesData->mes }} - 1] = {{ $mesData->total }};
    @endforeach
    
    // Crea el gráfico de líneas
    const mesCtx = document.getElementById('mesChart').getContext('2d');
    const mesChart = new Chart(mesCtx, {
        type: 'line', // Tipo de gráfico
        data: {
            labels: meses, // Etiquetas de los meses
            datasets: [{
                label: 'Solicitudes por Mes', // Etiqueta del dataset
                data: datosMeses, // Datos de cantidad
                fill: false, // Sin relleno bajo la línea
                backgroundColor: 'rgba(75, 192, 192, 0.7)', // Color de fondo
                borderColor: 'rgba(75, 192, 192, 1)', // Color de línea
                tension: 0.1 // Suavizado de la línea
            }]
        },
        options: {
            responsive: true, // Gráfico responsive
            maintainAspectRatio: false, // No mantener relación de aspecto
            scales: {
                y: {
                    beginAtZero: true // Eje Y comienza en 0
                }
            }
        }
    });
</script>
</body>
</html>