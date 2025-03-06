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
        <h1>Peticiones</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estatus</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peticiones as $peticion)
                    <tr>
                        <td>{{ $peticion->id }}</td>
                        <td>{{ $peticion->nombre }}</td>
                        <td>{{ $peticion->email }}</td>
                        <td>{{ $peticion->estatus }}</td>
                        <td>{{ $peticion->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>