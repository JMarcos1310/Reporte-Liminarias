<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Servidor Público</title>
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
            background-color: var(--wine-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            max-width: 500px;
            margin: 5rem auto;
            padding: 2.5rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(114, 47, 55, 0.15);
            border-top: 5px solid var(--wine-primary);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--wine-primary);
        }

        .login-header h2 {
            font-weight: 600;
        }

        .login-header i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--wine-primary);
        }

        .form-label {
            font-weight: 500;
            color: var(--wine-dark);
        }

        .form-control {
            border: 1px solid var(--wine-light);
            padding: 0.75rem 1rem;
            border-radius: 6px;
        }

        .form-control:focus {
            border-color: var(--wine-primary);
            box-shadow: 0 0 0 0.25rem rgba(114, 47, 55, 0.25);
        }

        .btn-wine {
            background-color: var(--wine-primary);
            color: white;
            border: none;
            padding: 0.75rem;
            font-weight: 500;
            width: 100%;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-wine:hover {
            background-color: var(--wine-dark);
            color: white;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--wine-dark);
        }

        .register-link a {
            color: var(--wine-primary);
            font-weight: 500;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
            color: var(--wine-dark);
        }

        .alert-success {
            background-color: #e8f3e8;
            border-color: #c3e6c3;
            color: #2d592d;
        }

        .alert-danger {
            background-color: #f8e8e8;
            border-color: #f5c6cb;
            color: #7a2328;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <i class="bi bi-person-badge"></i>
                <h2>Iniciar Sesión - Servidor Público</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any()))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <strong>Error en el inicio de sesión:</strong>
                    <ul class="mt-2 mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('servidor.login.submit') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-4">
                    <label for="contraseña" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="contraseña" id="contraseña" required>
                </div>

                <button type="submit" class="btn btn-wine mb-3">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                </button>
            </form>

            <div class="register-link">
                ¿No tienes cuenta? <a href="{{ route('servidor.registro') }}">Regístrate aquí</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>