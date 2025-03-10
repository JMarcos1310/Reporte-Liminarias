<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Administrador</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Bienvenido al Dashboard del Administrador</h1>
        <p>Aquí puedes gestionar las peticiones y otras tareas administrativas.</p>
        <a href="{{ route('admin.logout') }}" class="btn btn-danger">Cerrar Sesión</a>
    </div>
</body>
</html>