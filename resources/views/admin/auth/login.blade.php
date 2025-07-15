<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --wine-primary: #722f37;
            --wine-secondary: #8a3a44;
            --wine-light: #f8f2f3;
            --wine-dark: #5a232a;
            --wine-darker: #3d1a1f;
        }
        
        body {
            background-color: var(--wine-light);
            display: flex;
            min-height: 100vh;
            align-items: center;
            background-image: url('https://images.unsplash.com/photo-1518562180175-34a163b1c9c1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            background-color: rgba(114, 47, 55, 0.8);
        }
        
        .login-container {
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .card-header {
            background-color: var(--wine-primary);
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-bottom: 4px solid var(--wine-dark);
        }
        
        .card-header h4 {
            font-weight: 600;
            margin: 0;
            letter-spacing: 1px;
        }
        
        .card-body {
            padding: 2rem;
            background-color: white;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--wine-dark);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--wine-primary);
            box-shadow: 0 0 0 0.25rem rgba(114, 47, 55, 0.25);
        }
        
        .btn-primary {
            background-color: var(--wine-primary);
            border-color: var(--wine-primary);
            padding: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.5px;
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
        
        .alert {
            border-radius: 6px;
            padding: 1rem;
        }
        
        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            border-left: 4px solid #28a745;
            color: #28a745;
        }
        
        .text-danger {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .text-center a {
            color: var(--wine-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .text-center a:hover {
            color: var(--wine-dark);
            text-decoration: underline;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .input-group-text {
            background-color: var(--wine-light);
            border: 1px solid #ddd;
            color: var(--wine-dark);
        }
        
        /* Efecto para el contenedor del formulario */
        .form-container {
            position: relative;
            z-index: 1;
        }
        
        .form-container::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: linear-gradient(45deg, var(--wine-primary), var(--wine-dark));
            z-index: -1;
            border-radius: 15px;
            opacity: 0.7;
            filter: blur(10px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="form-container">
                    <div class="card shadow-lg">
                        <div class="card-header">
                            <h4><i class="fas fa-lock me-2"></i>Acceso Administrativo</h4>
                        </div>
                        
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success mb-4">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif
                            
                            @if($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <i class="fas fa-exclamation-circle me-2"></i>Credenciales incorrectas
                                </div>
                            @endif
                            
                            <form action="{{ route('admin.login.submit') }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="admin@example.com" required>
                                    </div>
                                    @error('email')
                                        <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-key me-2"></i>Contraseña
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                                    </div>
                                    @error('password')
                                        <span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary py-2">
                                        <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                                    </button>
                                </div>
                                
                               <!-- 
                                <div class="text-center pt-3 border-top">
                                    <a href="{{ route('admin.register') }}" class="d-inline-flex align-items-center">
                                        <i class="fas fa-user-plus me-2"></i>¿No tienes cuenta? Regístrate
                                    </a>
                                </div>
                            -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>