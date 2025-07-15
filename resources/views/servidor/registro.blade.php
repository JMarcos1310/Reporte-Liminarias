<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Servidor Público</title>
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
        
        .form-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(114, 47, 55, 0.1);
            border-left: 4px solid var(--wine-primary);
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--wine-primary), var(--wine-dark));
            color: white;
            padding: 30px 0;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .required-field::after {
            content: " *";
            color: var(--wine-primary);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="header-section text-center mb-5">
                    <h1 class="display-5 fw-bold mb-3"><i class="bi bi-person-plus"></i> Registro de Servidor Público</h1>
                    <p class="lead mb-0">Complete el formulario para registrarse</p>
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

                <div class="form-section">
                    <form method="POST" action="{{ route('servidor.registro.submit') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label required-field">Nombre completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label required-field">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contraseña" class="form-label required-field">Contraseña</label>
                                <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="confirmar_contraseña" class="form-label required-field">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirmar_contraseña" name="confirmar_contraseña" required>
                            </div>
                        </div>

                        <div class="row">
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

                            <div class="col-md-6 mb-3">
                                <label for="departamento_id" class="form-label required-field">Departamento</label>
                                <select class="form-select" id="departamento_id" name="departamento_id" required>
                                    <option value="" selected disabled>Selecciona un departamento</option>
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}" {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                            {{ $departamento->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono (opcional)</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}">
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-wine btn-lg">
                                <i class="bi bi-person-plus"></i> Registrarse
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('servidor.login') }}" class="wine-text">¿Ya tienes cuenta? Inicia sesión</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>