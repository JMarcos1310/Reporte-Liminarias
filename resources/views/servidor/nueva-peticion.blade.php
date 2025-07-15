<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud - Servidor Público</title>
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
        
        .btn-outline-wine {
            color: var(--wine-primary);
            border-color: var(--wine-primary);
        }
        
        .btn-outline-wine:hover {
            color: white;
            background-color: var(--wine-primary);
        }
        
        #map {
            height: 400px;
            width: 100%;
            border-radius: 8px;
            border: 2px solid var(--wine-light);
            margin-bottom: 1rem;
        }
        
        .photo-preview {
            max-width: 100%;
            max-height: 300px;
            display: none;
            margin-top: 10px;
            border-radius: 8px;
            border: 2px solid var(--wine-light);
        }
        
        .form-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(114, 47, 55, 0.1);
            border-left: 4px solid var(--wine-primary);
        }
        
        .location-btn {
            position: relative;
        }
        
        .location-btn .spinner-border {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold wine-text"><i class="bi bi-plus-circle"></i> Nueva Solicitud</h2>
                <p class="text-muted mb-0">Servidor: {{ $servidor->nombre }}</p>
            </div>
            <a href="{{ route('servidor.dashboard') }}" class="btn btn-outline-wine">
                <i class="bi bi-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <strong>Error en el formulario:</strong>
            <ul class="mt-2 mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form id="reportForm" action="{{ route('servidor.peticiones.nueva.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-section">
                <h3 class="mb-4 wine-text"><i class="bi bi-geo-alt"></i> Ubicación del problema</h3>
                
                <div id="map"></div>
                <div class="d-flex justify-content-between mt-2">
                    <button type="button" id="getLocationBtn" class="btn btn-wine location-btn">
                        <i class="bi bi-geo-alt-fill"></i> Obtener mi ubicación
                        <span class="spinner-border spinner-border-sm"></span>
                    </button>
                    <small class="text-muted">Arrastra el marcador para ajustar la ubicación</small>
                </div>

                <div class="row mt-3">
                    <div class="col-md-8 mb-3">
                        <label for="direccion" class="form-label">Dirección exacta</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="colonia" class="form-label">Colonia o barrio</label>
                        <input type="text" class="form-control" id="colonia" name="colonia" value="{{ old('colonia') }}" required>
                    </div>
                </div>

                <input type="hidden" id="latitud" name="latitud" value="{{ old('latitud') }}">
                <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud') }}">
            </div>

            <div class="form-section">
                <h3 class="mb-4 wine-text"><i class="bi bi-card-checklist"></i> Detalles del Reporte</h3>
                
                <div class="mb-3">
                    <label for="tipo_servicio_id" class="form-label">Tipo de servicio</label>
                    <select class="form-select" id="tipo_servicio_id" name="tipo_servicio_id" required>
                        <option value="" selected disabled>Selecciona un servicio</option>
                        @foreach($tiposServicio as $servicio)
                            <option value="{{ $servicio->id }}" {{ old('tipo_servicio_id') == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label">Descripción del problema</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="4" required>{{ old('observaciones') }}</textarea>
                    <small class="text-muted">Describe con detalle el problema que deseas reportar</small>
                </div>
            </div>

            <div class="form-section">
                <h3 class="mb-4 wine-text"><i class="bi bi-camera"></i> Evidencia fotográfica</h3>
                
                <div class="mb-3">
                    <label for="evidencia_foto" class="form-label">Sube una foto del problema</label>
                    <input class="form-control" type="file" id="evidencia_foto" name="evidencia_foto" accept="image/*" capture="environment" required>
                    <small class="text-muted">Formatos aceptados: JPEG, PNG, GIF. Tamaño máximo: 5MB</small>
                </div>

                <div class="text-center">
                    <img id="photoPreview" class="photo-preview img-fluid" alt="Vista previa de la foto">
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-wine btn-lg">
                    <i class="bi bi-send-fill"></i> Enviar solicitud
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mapa y geolocalización
        let map;
        let marker;
        let defaultPosition = { lat: 19.4326, lng: -99.1332 };
        
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: defaultPosition,
            });

            marker = new google.maps.Marker({
                position: defaultPosition,
                map: map,
                draggable: true,
                title: "Ubicación del problema"
            });

            marker.addListener("dragend", function() {
                updatePositionFields(marker.getPosition());
            });

            // Si hay valores antiguos en el formulario
            const oldLat = parseFloat(document.getElementById('latitud').value) || defaultPosition.lat;
            const oldLng = parseFloat(document.getElementById('longitud').value) || defaultPosition.lng;
            
            const oldPosition = { lat: oldLat, lng: oldLng };
            marker.setPosition(oldPosition);
            map.setCenter(oldPosition);
            updatePositionFields(oldPosition);
        }

        function updatePositionFields(position) {
            document.getElementById('latitud').value = position.lat();
            document.getElementById('longitud').value = position.lng();
            
            // Geocodificación inversa para obtener dirección
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: position }, (results, status) => {
                if (status === "OK" && results[0]) {
                    document.getElementById('direccion').value = results[0].formatted_address;
                    
                    // Buscar componente de colonia
                    const neighborhood = results[0].address_components.find(component => 
                        component.types.includes('neighborhood') || 
                        component.types.includes('sublocality')
                    );
                    
                    if (neighborhood) {
                        document.getElementById('colonia').value = neighborhood.long_name;
                    }
                }
            });
        }

        document.getElementById('getLocationBtn').addEventListener('click', function() {
            const btn = this;
            const spinner = btn.querySelector('.spinner-border');
            
            btn.disabled = true;
            spinner.style.display = 'inline-block';
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        
                        marker.setPosition(pos);
                        map.setCenter(pos);
                        updatePositionFields(pos);
                        
                        btn.disabled = false;
                        spinner.style.display = 'none';
                    },
                    (error) => {
                        console.error("Error al obtener ubicación: ", error);
                        alert("No se pudo obtener tu ubicación. Por favor selecciona manualmente en el mapa.");
                        btn.disabled = false;
                        spinner.style.display = 'none';
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                alert("Tu navegador no soporta geolocalización. Por favor selecciona manualmente en el mapa.");
                btn.disabled = false;
                spinner.style.display = 'none';
            }
        });

        // Vista previa de la foto
        document.getElementById('evidencia_foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.getElementById('photoPreview');
                    img.src = event.target.result;
                    img.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Validación del formulario
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            if (!document.getElementById('latitud').value || !document.getElementById('longitud').value) {
                e.preventDefault();
                alert('Por favor selecciona una ubicación en el mapa.');
                return false;
            }
            
            const fileInput = document.getElementById('evidencia_foto');
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('Por favor sube una foto como evidencia.');
                return false;
            }
            
            return true;
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6PDfZtM1Y7gdBwTxtY9U0ggFcx_Whx4o&callback=initMap&libraries=places" async defer></script>
</body>
</html>