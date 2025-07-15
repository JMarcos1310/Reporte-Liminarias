<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Solicitudes</title>
    <style>
        :root {
            --wine-primary: #5a232a;       /* Vino tinto oscuro */
            --wine-secondary: #722f37;     /* Vino tinto principal */
            --wine-tertiary: #8a3a44;      /* Vino tinto claro */
            --wine-accent: #a04550;        /* Toques de vino más claro */
            --wine-light: #f8f2f3;         /* Fondo muy claro con tono vino */
            --wine-dark: #3d161b;          /* Vino muy oscuro para texto */
            --wine-badge-light: #d4a5ac;   /* Para fondos de badges claros */
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: var(--wine-dark);
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--wine-light);
        }
        
        .title {
            font-size: 22px;
            font-weight: bold;
            color: var(--wine-primary);
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 14px;
            color: var(--wine-tertiary);
        }
        
        .logo {
            height: 60px;
            margin-bottom: 10px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        .table th {
            background-color: var(--wine-light);
            color: var(--wine-primary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            min-width: 70px;
            text-align: center;
        }
        
        .badge-warning {
            background-color: #e6c229;
            color: var(--wine-dark);
        }
        
        .badge-info {
            background-color: var(--wine-tertiary);
            color: white;
        }
        
        .badge-success {
            background-color: #5a8a3a;  /* Verde con tono vino */
            color: white;
        }
        
        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: right;
            color: var(--wine-tertiary);
            padding-top: 10px;
            border-top: 1px solid var(--wine-light);
        }
        
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: var(--wine-light);
            border-radius: 5px;
            font-size: 13px;
            border-left: 4px solid var(--wine-primary);
        }
        
        .summary-item {
            display: inline-block;
            margin-right: 20px;
        }
        
        .summary-value {
            font-weight: bold;
            color: var(--wine-primary);
        }
        
        .no-results {
            text-align: center;
            padding: 20px;
            color: var(--wine-tertiary);
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- El resto de tu código HTML permanece exactamente igual -->
    <div class="header">
        @if(file_exists(public_path('imagenes/logo-reporte.png')))
        <img src="{{ asset('imagenes/logo-reporte.png') }}" class="logo" alt="Logo">
        @endif
        <div class="title">Reporte de Solicitudes Ciudadanas</div>
        <div class="subtitle">
            Comunidad: {{ $comunidad ?? 'Todas las comunidades' }} | 
            Período: 
            @php
            $periodo = 'Todos los meses';
            if(isset($mes) && $mes) {
                $fecha = DateTime::createFromFormat('!m', $mes);
                if($fecha !== false) {
                    $periodo = $fecha->format('F');
                }
            }
        @endphp
        Período: {{ $periodo }} {{ $year ?? '' }}
        </div>
    </div>
    
    @php
        // Pre-calcular los contadores para evitar múltiples iteraciones
        $total = count($solicitudes);
        $pendientes = 0;
        $en_proceso = 0;
        $resueltas = 0;
        
        foreach($solicitudes as $solicitud){
            switch($solicitud->estatus){
                case 'pendiente': $pendientes++; break;
                case 'en proceso': $en_proceso++; break;
                case 'resuelto': $resueltas++; break;
            }
        }
    @endphp
    
    <div class="summary">
        <div class="summary-item">Total solicitudes: <span class="summary-value">{{ $total }}</span></div>
        <div class="summary-item">Pendientes: <span class="summary-value">{{ $pendientes }}</span></div>
        <div class="summary-item">En proceso: <span class="summary-value">{{ $en_proceso }}</span></div>
        <div class="summary-item">Resueltas: <span class="summary-value">{{ $resueltas }}</span></div>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th width="12%"># Solicitud</th>
                <th width="15%">Servicio</th>
                <th width="15%">Comunidad</th>
                <th width="15%">Ciudadano</th>
                <th width="20%">Dirección</th>
                <th width="10%">Estatus</th>
                <th width="13%">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @if($total > 0)
                @foreach($solicitudes as $solicitud)
                <tr>
                    <td>{{ $solicitud->numero_solicitud }}</td>
                    <td>{{ $solicitud->servicio }}</td>
                    <td>{{ $solicitud->comunidad }}</td>
                    <td>{{ $solicitud->ciudadano ?? 'Anónimo' }}</td>
                    <td>{{ $solicitud->direccion }}</td>
                    <td>
                        @switch($solicitud->estatus)
                            @case('pendiente')<span class="badge badge-warning">Pendiente</span>@break
                            @case('en proceso')<span class="badge badge-info">En Proceso</span>@break
                            @default<span class="badge badge-success">Resuelto</span>
                        @endswitch
                    </td>
                    <td>{{ date('d/m/Y', strtotime($solicitud->created_at)) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="no-results">
                        No se encontraron solicitudes con los filtros seleccionados
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    
    <div class="footer">
        Generado el: {{ date('d/m/Y H:i') }} | Sistema de Reportes Ciudadanos
    </div>
</body>
</html>