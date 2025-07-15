<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Servidor Público</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --wine-primary: #722f37;
            --wine-secondary: #8a3a44;
            --wine-light: #f8f2f3;
            --wine-dark: #5a232a;
        }
        
        body {
            background-color: #f9f9f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .wine-bg {
            background-color: var(--wine-primary);
        }
        
        .wine-text {
            color: var(--wine-primary);
        }
        
        .btn-wine {
            background-color: var(--wine-primary);
            color: white;
            border-color: var(--wine-dark);
        }
        
        .btn-wine:hover {
            background-color: var(--wine-dark);
            color: white;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--wine-primary), var(--wine-dark));
            color: white;
            padding: 2rem 0;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        
        .card-header {
            background-color: var(--wine-primary);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .badge-pendiente {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-proceso {
            background-color: #0dcaf0;
            color: white;
        }
        
        .badge-resuelto {
            background-color: #198754;
            color: white;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(114, 47, 55, 0.05);
        }
        
        .photo-thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        #map {
            height: 300px;
            width: 100%;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid #ddd;
        }
        
        .map-container {
            position: relative;
            margin-bottom: 20px;
        }
        
        .coordinates-info {
            font-size: 0.8rem;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Encabezado -->
        <div class="header-section text-center">
            <div class="d-flex justify-content-between align-items-center">
                <img src="{{ asset('imagenes/logo.png') }}" alt="Logo" style="height: 90px;">
                <div>
                    <h1 class="mb-3"><i class="bi bi-speedometer2"></i> Panel del Servidor Público</h1>
                    <p class="mb-0">Bienvenido, {{ $servidor->nombre }}</p>
                </div>
                <img src="{{ asset('imagenes/logoh.png') }}" alt="Logo" style="height: 90px;">
            </div>
        </div>

        <!-- Barra de acciones -->
        <div class="d-flex justify-content-between mb-4">
            <h3 class="wine-text"><i class="bi bi-list-ul"></i> Mis Solicitudes</h3>
            <div>
                <a href="{{ route('servidor.peticiones.nueva') }}" class="btn btn-wine me-2">
                    <i class="bi bi-plus-circle"></i> Nueva Solicitud
                </a>
                <a href="{{ route('servidor.logout') }}" class="btn btn-outline-wine">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>

        <!-- Listado de solicitudes -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-card-checklist"></i> Historial de Solicitudes</h5>
            </div>
            <div class="card-body">
                @if($peticiones->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #adb5bd;"></i>
                        <h4 class="mt-3 wine-text">No hay solicitudes registradas</h4>
                        <p class="text-muted">Comienza creando tu primera solicitud</p>
                        <a href="{{ route('servidor.peticiones.nueva') }}" class="btn btn-wine mt-3">
                            <i class="bi bi-plus-circle"></i> Crear Solicitud
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N° Solicitud</th>
                                    <th>Tipo</th>
                                    <th>Ubicación</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peticiones as $peticion)
                                <tr>
                                    <td>{{ $peticion->numero_solicitud }}</td>
                                    <td>
                                        @php
                                            $tipo = DB::table('tipos_servicio')->where('id', $peticion->tipo_servicio_id)->first();
                                            echo $tipo->nombre;
                                        @endphp
                                    </td>
                                    <td>{{ $peticion->direccion }}</td>
                                    <td>{{ date('d/m/Y H:i', strtotime($peticion->created_at)) }}</td>
                                    <td>
                                        @if($peticion->estatus == 'pendiente')
                                            <span class="badge badge-pendiente">Pendiente</span>
                                        @elseif($peticion->estatus == 'en proceso')
                                            <span class="badge badge-proceso">En Proceso</span>
                                        @else
                                            <span class="badge badge-resuelto">Resuelto</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-wine" data-bs-toggle="modal" data-bs-target="#modalDetalle{{ $peticion->id }}">
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modales de detalle -->
    @foreach($peticiones as $peticion)
    <div class="modal fade" id="modalDetalle{{ $peticion->id }}" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header wine-bg text-white">
                    <h5 class="modal-title" id="modalDetalleLabel">Detalle de Solicitud #{{ $peticion->numero_solicitud }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tipo de Servicio:</strong> 
                                @php
                                    $tipo = DB::table('tipos_servicio')->where('id', $peticion->tipo_servicio_id)->first();
                                    echo $tipo->nombre;
                                @endphp
                            </p>
                            <p><strong>Dirección:</strong> {{ $peticion->direccion }}</p>
                            <p><strong>Colonia:</strong> {{ $peticion->colonia }}</p>
                            
                            <div class="map-container">
                                <div id="map-{{ $peticion->id }}"></div>
                                <div class="coordinates-info">
                                    Coordenadas: {{ $peticion->latitud }}, {{ $peticion->longitud }}
                                </div>
                            </div>
                            
                            <p><strong>Fecha de creación:</strong> {{ date('d/m/Y H:i', strtotime($peticion->created_at)) }}</p>
                            <p><strong>Estado:</strong> 
                                @if($peticion->estatus == 'pendiente')
                                    <span class="badge badge-pendiente">Pendiente</span>
                                @elseif($peticion->estatus == 'en proceso')
                                    <span class="badge badge-proceso">En Proceso</span>
                                @else
                                    <span class="badge badge-resuelto">Resuelto</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Observaciones:</strong></p>
                            <p class="mb-4">{{ $peticion->observaciones }}</p>
                            
                            <p><strong>Evidencia Fotográfica:</strong></p>
                            <img src="{{ asset('storage/' . $peticion->evidencia_foto) }}" class="img-fluid rounded" alt="Evidencia">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-wine" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6PDfZtM1Y7gdBwTxtY9U0ggFcx_Whx4o&callback=Function.prototype&libraries=places" async defer></script>
    
    <script>
        // Inicializar mapas cuando se abren los modales
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($peticiones as $peticion)
            document.getElementById('modalDetalle{{ $peticion->id }}').addEventListener('shown.bs.modal', function() {
                initMap({{ $peticion->id }}, {{ $peticion->latitud }}, {{ $peticion->longitud }}, "{{ $peticion->numero_solicitud }}");
            });
            @endforeach
        });

        function initMap(id, lat, lng, solicitud) {
            const location = { lat: parseFloat(lat), lng: parseFloat(lng) };
            
            const map = new google.maps.Map(document.getElementById(`map-${id}`), {
                zoom: 17,
                center: location,
                mapTypeId: "hybrid",
                styles: [
                    {
                        featureType: "poi",
                        stylers: [{ visibility: "off" }]
                    },
                    {
                        featureType: "transit",
                        elementType: "labels.icon",
                        stylers: [{ visibility: "off" }]
                    }
                ]
            });
            
            // Marcador personalizado
            new google.maps.Marker({
                position: location,
                map: map,
                title: "Ubicación de la solicitud " + solicitud,
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                    scaledSize: new google.maps.Size(40, 40)
                }
            });
            
            // Círculo de precisión
            new google.maps.Circle({
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.2,
                map: map,
                center: location,
                radius: 15
            });
            
            // Ventana de información
            const infowindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 10px; max-width: 250px;">
                        <h5 style="margin: 0 0 5px 0; color: #722f37;">Solicitud ${solicitud}</h5>
                        <p style="margin: 0;">Ubicación reportada</p>
                    </div>
                `
            });
            
            infowindow.open(map);
        }
    </script>
</body>
</html>