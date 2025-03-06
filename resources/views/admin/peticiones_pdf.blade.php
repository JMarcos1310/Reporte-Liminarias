<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Peticiones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Reporte de Peticiones</h1>
    <table>
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
</body>
</html>