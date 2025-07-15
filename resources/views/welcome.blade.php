<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8"> <!-- Codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design -->
    <title>Sistema de Reportes Ciudadanos</title> <!-- Título de la página -->
    
    <!-- Enlaces a hojas de estilo y librerías externas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"> <!-- Bootstrap Icons -->
    
    <style>
        /* Variables CSS personalizadas para los colores del tema */
        :root {
            --wine-primary: #722f37; /* Color principal (vino) */
            --wine-secondary: #8a3a44; /* Color secundario */
            --wine-light: #f8f2f3; /* Color claro */
            --wine-dark: #5a232a; /* Color oscuro */
        }

        /* Estilos generales del cuerpo */
        body {
            background-color: #f9f9f9; /* Fondo gris claro */
            padding-top: 160px; /* Espacio para la barra de logos fija */
        }

        /* Barra superior con logos */
        .logo-bar {
            background: linear-gradient(135deg, var(--wine-primary), var(--wine-dark)); /* Degradado */
            color: white; /* Color de texto */
            padding: 1rem 0; /* Espaciado interno */
            position: fixed; /* Posición fija */
            top: 0; /* Pegado arriba */
            width: 100%; /* Ancho completo */
            z-index: 1030; /* Z-index alto para superposición */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra */
        }

        /* Contenedor de logos */
        .logo-bar .logo-container {
            display: flex; /* Diseño flexible */
            align-items: center; /* Centrado vertical */
            justify-content: space-between; /* Espacio entre elementos */
            flex-wrap: wrap; /* Permite envolver elementos */
            gap: 1rem; /* Espacio entre elementos */
        }

        /* Estilos para logos */
        .main-logo, .secondary-logo {
            height: 80px; /* Altura fija */
            width: auto; /* Ancho automático */
            max-width: 180px; /* Ancho máximo */
            object-fit: contain; /* Ajuste de imagen */
            transition: transform 0.3s ease; /* Transición para efecto hover */
            flex-shrink: 0; /* Evita que se encojan */
        }

        /* Efecto hover en logos */
        .main-logo:hover, .secondary-logo:hover {
            transform: scale(1.05); /* Escala ligeramente */
        }

        /* Título principal */
        .logo-bar h1 {
            margin: 0 auto; /* Centrado */
            text-align: center; /* Texto centrado */
            font-size: 2rem; /* Tamaño de fuente */
            font-weight: bold; /* Negrita */
        }
        
        /* Media queries para diseño responsive */
        @media (max-width: 768px) {
            .logo-bar h1 {
                font-size: 1.5rem; /* Tamaño reducido en móviles */
            }

            .main-logo, .secondary-logo {
                height: 60px; /* Altura reducida */
                max-width: 140px; /* Ancho máximo reducido */
            }
        }

        /* Clases utilitarias */
        .wine-text { color: var(--wine-primary); } /* Texto color vino */

        /* Botones personalizados */
        .btn-wine {
            background-color: var(--wine-primary); /* Color de fondo */
            color: white; /* Color de texto */
            border-color: var(--wine-dark); /* Color de borde */
        }

        .btn-wine:hover {
            background-color: var(--wine-dark); /* Color hover */
            color: white; /* Color de texto */
        }

        /* Secciones del formulario */
        .form-section {
            background: white; /* Fondo blanco */
            padding: 25px; /* Espaciado interno */
            border-radius: 10px; /* Bordes redondeados */
            margin-bottom: 25px; /* Margen inferior */
            box-shadow: 0 2px 10px rgba(114, 47, 55, 0.1); /* Sombra sutil */
            border-left: 4px solid var(--wine-primary); /* Borde izquierdo */
        }

        /* Vista previa de foto */
        .photo-preview {
            max-width: 100%; /* Ancho máximo */
            max-height: 300px; /* Altura máxima */
            display: none; /* Oculto inicialmente */
            margin-top: 10px; /* Margen superior */
            border-radius: 8px; /* Bordes redondeados */
            border: 2px solid var(--wine-light); /* Borde sutil */
        }

        /* Mapa */
        #map {
            height: 400px; /* Altura fija */
            width: 100%; /* Ancho completo */
            border-radius: 8px; /* Bordes redondeados */
            border: 2px solid var(--wine-light); /* Borde sutil */
        }

        /* Indicador de campos requeridos */
        .required-field::after {
            content: " *"; /* Agrega asterisco */
            color: var(--wine-primary); /* Color vino */
        }

        /* Estilos para inputs enfocados */
        select.form-select:focus, input.form-control:focus, textarea.form-control:focus {
            border-color: var(--wine-primary); /* Color de borde */
            box-shadow: 0 0 0 0.25rem rgba(114, 47, 55, 0.25); /* Sombra */
        }

        /* Clase para campos inválidos */
        .is-invalid { border-color: #dc3545; } /* Rojo de Bootstrap */

        /* Botón de ubicación con spinner */
        .location-btn {
            position: relative; /* Posición relativa para spinner */
        }

        .location-btn .spinner-border {
            position: absolute; /* Posición absoluta */
            top: 50%; /* Centrado vertical */
            left: 50%; /* Centrado horizontal */
            transform: translate(-50%, -50%); /* Centrado preciso */
            display: none; /* Oculto inicialmente */
        }
    </style>
</head>
<body>
    <!-- Barra superior con logos -->
    <div class="logo-bar">
        <div class="container logo-container">
            <!-- Logo principal -->
            <img src="{{ asset('imagenes/logo.png') }}" alt="Logo principal" class="main-logo">
            <!-- Título con icono -->
            <h1><i class="bi bi-megaphone"></i> Reporte Ciudadano</h1>
            <!-- Logo secundario -->
            <img src="{{ asset('imagenes/logoh.png') }}" alt="Logo secundario" class="secondary-logo">
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10"> 
                <!-- Mensaje de éxito -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Mensajes de error -->
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

                <!-- Formulario de reporte -->
                <form id="reportForm" action="{{ route('peticiones.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf <!-- Token CSRF para protección -->

                    <!-- Sección de información del ciudadano -->
                    <div class="form-section">
                        <h3 class="mb-4 wine-text"><i class="bi bi-person"></i> Información del ciudadano</h3>
                        
                        <!-- Campo de nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label required-field">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        </div>

                        <div class="row">
                            <!-- Campo de email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label required-field">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <!-- Campo de teléfono -->
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono (opcional)</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}">
                            </div>
                        </div>

                        <!-- Campo de observaciones -->
                        <div class="mb-3">
                            <label for="observaciones" class="form-label required-field">Referencias</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="4" required>{{ old('observaciones') }}</textarea>
                            <small class="text-muted">Para brindar un mejor servicio proporciona referencias de la luminaria</small>
                        </div>
                    </div>

                    <!-- Sección de ubicación -->
                    <div class="form-section">
                        <h3 class="mb-4 wine-text"><i class="bi bi-geo-alt"></i> Ubicación del problema</h3>
                        
                        <!-- Mapa -->
                        <div class="mb-3">
                            <div id="map"></div>
                            <div class="d-flex justify-content-between mt-2">
                                <!-- Botón para obtener ubicación -->
                                <button type="button" id="getLocationBtn" class="btn btn-wine location-btn">
                                    <i class="bi bi-geo-alt-fill"></i> Obtener mi ubicación
                                    <span class="spinner-border spinner-border-sm"></span>
                                </button>
                                <small class="text-muted">Arrastra el marcador para ajustar la ubicación</small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Selector de comunidad -->
                            <div class="col-md-6 mb-3">
                                <label for="comunidad_id" class="form-label required-field">Comunidad</label>
                                <select class="form-select" id="comunidad_id" name="comunidad_id" required>
                                    <option value="" selected disabled>Selecciona una comunidad</option>
                                    @foreach($comunidades as $comunidad)
                                        <option value="{{ $comunidad->id }}" {{ old('comunidad_id') == $comunidad->id ? 'selected' : '' }}>
                                            {{ $comunidad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Selector de tipo de servicio -->
                            <div class="col-md-6 mb-3">
                                <label for="tipo_servicio_id" class="form-label required-field">Tipo de servicio</label>
                                <select class="form-select" id="tipo_servicio_id" name="tipo_servicio_id" required>
                                    <option value="" selected disabled>Selecciona un servicio</option>
                                    @foreach($tiposServicio as $servicio)
                                        <option value="{{ $servicio->id }}" {{ old('tipo_servicio_id') == $servicio->id ? 'selected' : '' }}>
                                            {{ $servicio->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Campo de dirección -->
                        <div class="mb-3">
                            <label for="direccion" class="form-label required-field">Dirección exacta</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                        </div>

                        <!-- Campo de colonia -->
                        <div class="mb-3">
                            <label for="colonia" class="form-label required-field">Colonia o barrio</label>
                            <input type="text" class="form-control" id="colonia" name="colonia" value="{{ old('colonia') }}" required>
                        </div>

                        <!-- Campos ocultos para coordenadas -->
                        <input type="hidden" id="latitud" name="latitud" value="{{ old('latitud') }}">
                        <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud') }}">
                    </div>

                    <!-- Sección de evidencia fotográfica -->
                    <div class="form-section">
                        <h3 class="mb-4 wine-text"><i class="bi bi-camera"></i> Evidencia fotográfica</h3>
                        
                        <!-- Input para subir foto -->
                        <div class="mb-3">
                            <label for="evidencia_foto" class="form-label required-field">Sube una foto del problema</label>
                            <input class="form-control" type="file" id="evidencia_foto" name="evidencia_foto" accept="image/*" capture="environment" required>
                            <small class="text-muted">Formatos aceptados: JPEG, PNG, GIF. Tamaño máximo: 5MB</small>
                        </div>

                        <!-- Vista previa de la foto -->
                        <div class="text-center">
                            <img id="photoPreview" class="photo-preview img-fluid" alt="Vista previa de la foto">
                        </div>
                    </div>

                    <!-- Botón de envío -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-wine btn-lg">
                            <i class="bi bi-send-fill"></i> Enviar reporte
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts necesarios -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    
    <script>
        // Mapa y geolocalización
        let map;
        let marker;
        let defaultPosition = { lat: 19.4326, lng: -99.1332 }; // Centro de México como fallback
        
        // Inicialización del mapa
        function initMap() {
            // Crear instancia del mapa
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15, // Nivel de zoom inicial
                center: defaultPosition, // Posición inicial
                styles: [
                    {
                        featureType: "poi", // Puntos de interés
                        elementType: "labels",
                        stylers: [{ visibility: "off" }] // Ocultar etiquetas
                    }
                ]
            });

            // Crear marcador arrastrable
            marker = new google.maps.Marker({
                position: defaultPosition, // Posición inicial
                map: map, // Asociar al mapa
                draggable: true, // Permitir arrastrar
                title: "Ubicación del problema", // Tooltip
                icon: {
                    url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png" // Icono personalizado
                }
            });

            // Evento cuando se termina de arrastrar el marcador
            marker.addListener("dragend", function() {
                updatePositionFields(marker.getPosition()); // Actualizar campos
            });

            // Si hay valores antiguos (después de validación fallida)
            const oldLat = parseFloat(document.getElementById('latitud').value);
            const oldLng = parseFloat(document.getElementById('longitud').value);
            
            if (!isNaN(oldLat) && !isNaN(oldLng)) {
                const oldPosition = { lat: oldLat, lng: oldLng };
                marker.setPosition(oldPosition); // Posicionar marcador
                map.setCenter(oldPosition); // Centrar mapa
                updatePositionFields(oldPosition); // Actualizar campos
            }
        }

        // Función para actualizar campos de posición y dirección
        function updatePositionFields(position) {
            // Actualizar campos ocultos de latitud y longitud
            document.getElementById('latitud').value = position.lat();
            document.getElementById('longitud').value = position.lng();
            
            // Geocodificación inversa para obtener dirección
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: position }, (results, status) => {
                if (status === "OK" && results[0]) {
                    // Actualizar campo de dirección con la dirección formateada
                    document.getElementById('direccion').value = results[0].formatted_address;
                    
                    // Buscar componente de colonia en la respuesta
                    const neighborhood = results[0].address_components.find(component => 
                        component.types.includes('neighborhood') || 
                        component.types.includes('sublocality')
                    );
                    
                    // Si se encontró colonia, actualizar campo
                    if (neighborhood) {
                        document.getElementById('colonia').value = neighborhood.long_name;
                    }
                }
            });
        }

        // Evento para el botón de obtener ubicación
        document.getElementById('getLocationBtn').addEventListener('click', function() {
            const btn = this;
            const spinner = btn.querySelector('.spinner-border');
            
            // Deshabilitar botón y mostrar spinner
            btn.disabled = true;
            spinner.style.display = 'inline-block';
            
            // Verificar si el navegador soporta geolocalización
            if (navigator.geolocation) {
                // Obtener posición actual
                navigator.geolocation.getCurrentPosition(
                    // Callback de éxito
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        
                        // Mover marcador y centrar mapa
                        marker.setPosition(pos);
                        map.setCenter(pos);
                        updatePositionFields(pos);
                        
                        // Restaurar botón
                        btn.disabled = false;
                        spinner.style.display = 'none';
                    },
                    // Callback de error
                    (error) => {
                        console.error("Error al obtener ubicación: ", error);
                        alert("No se pudo obtener tu ubicación. Por favor selecciona manualmente en el mapa.");
                        btn.disabled = false;
                        spinner.style.display = 'none';
                    },
                    // Opciones
                    { 
                        enableHighAccuracy: true, // Alta precisión
                        timeout: 10000, // Tiempo máximo de espera
                        maximumAge: 0 // No usar caché de posición
                    }
                );
            } else {
                // Navegador no soporta geolocalización
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
                // Cuando se cargue el archivo
                reader.onload = function(event) {
                    const img = document.getElementById('photoPreview');
                    img.src = event.target.result; // Establecer src de la imagen
                    img.style.display = 'block'; // Mostrar imagen
                };
                reader.readAsDataURL(file); // Leer archivo como URL
            }
        });

        // Validación del formulario antes de enviar
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            // Validar que se haya seleccionado ubicación
            if (!document.getElementById('latitud').value || !document.getElementById('longitud').value) {
                e.preventDefault();
                alert('Por favor selecciona una ubicación en el mapa.');
                return false;
            }
            
            // Validar que se haya subido una foto
            const fileInput = document.getElementById('evidencia_foto');
            if (fileInput.files.length === 0) {
                e.preventDefault();
                alert('Por favor sube una foto como evidencia.');
                return false;
            }
            
            // Validar campos requeridos
            const requiredFields = document.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid'); // Marcar como inválido
                } else {
                    field.classList.remove('is-invalid'); // Quitar marca de inválido
                }
            });
            
            // Si hay campos inválidos, prevenir envío
            if (!isValid) {
                e.preventDefault();
                alert('Por favor completa todos los campos obligatorios marcados con *.');
                return false;
            }
            
            return true;
        });
    </script>
    <!-- API de Google Maps con clave y callback -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6PDfZtM1Y7gdBwTxtY9U0ggFcx_Whx4o&callback=initMap&libraries=places" async defer></script>
</body>
</html>