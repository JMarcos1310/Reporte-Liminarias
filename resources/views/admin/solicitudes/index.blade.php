@extends('admin.layouts.app') @section('content')
<div class="container-fluid p-4">
    <div class="card">
        <div class="card-header">
            <h5 class="m-0">Gestión de Solicitudes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th># Solicitud</th>
                            <th>Servicio</th>
                            <th>Comunidad</th>
                            <th>Ciudadano</th>
                            <th>Estatus</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $solicitud)
                            <tr>
                                <td>{{ $solicitud->numero_solicitud }}</td>
                                <td>{{ $solicitud->servicio }}</td>
                                <td>{{ $solicitud->comunidad }}</td>
                                <td>{{ $solicitud->ciudadano ?? 'Anónimo' }}</td>
                                <td>
                                    @if($solicitud->estatus == 'pendiente')
                                        <span class="badge bg-warning">Pendiente</span>
                                    @elseif($solicitud->estatus == 'en proceso')
                                        <span class="badge bg-info">En Proceso</span>
                                    @else
                                        <span class="badge bg-success">Resuelto</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.solicitudes.edit', $solicitud->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No hay solicitudes registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection