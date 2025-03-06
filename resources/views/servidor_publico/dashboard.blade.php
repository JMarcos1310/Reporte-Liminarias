<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
@section('content')
    <div class="container">
        <h1>Dashboard del Servidor Público</h1>
        <a href="{{ route('servidor_publico.peticiones') }}" class="btn btn-primary">Ver Peticiones</a>
    </div>

</body>
</html>