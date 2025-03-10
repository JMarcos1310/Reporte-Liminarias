<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <!-- Agregar estilos CSS -->
</head>
<body>
    <div class="container mt-5">
        <h1>Bienvenido</h1>
        <p>Por favor, complete el siguiente formulario para generar una solicitud.</p>

        <form action="{{ route('peticiones.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="comunidad_id" class="form-label">Comunidad</label>
                <select class="form-select" id="comunidad_id" name="comunidad_id" required>
                    @foreach($comunidades as $comunidad)
                        <option value="{{ $comunidad->id }}">{{ $comunidad->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="tipo_servicio_id" class="form-label">Tipo de Servicio</label>
                <select class="form-select" id="tipo_servicio_id" name="tipo_servicio_id" required>
                    @foreach($tiposServicio as $tipoServicio)
                        <option value="{{ $tipoServicio->id }}">{{ $tipoServicio->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Número de Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono">
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion">
            </div>
            <div class="mb-3">
                <label for="colonia" class="form-label">Colonia</label>
                <input type="text" class="form-control" id="colonia" name="colonia">
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</body>
</html>