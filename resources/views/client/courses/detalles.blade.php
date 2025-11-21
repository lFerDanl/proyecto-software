@extends('layouts.client')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Detalles del Curso: {{ $curso->nombre }}</h1>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if($puedeGestionar)
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#agregarMaterialModal">
            Agregar Material Didáctico
        </button>
    @endif

    <div class="row">
        <div class="{{ $puedeGestionar ? 'col-md-6' : 'col-12' }}">
            <h2 class="text-primary">Materiales Didácticos</h2>
            <ul class="list-group">
                @forelse ($materiales as $material)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $material->descripcion }}
                        <a href="{{ asset('storage/'.$material->archivo) }}" target="_blank" class="btn btn-sm btn-success">Descargar</a>
                    </li>
                @empty
                    <li class="list-group-item">No hay materiales disponibles.</li>
                @endforelse
            </ul>
        </div>

        @if($puedeGestionar)
            <div class="col-md-6">
                <h2 class="text-primary">Usuarios Inscritos</h2>
                <ul class="list-group">
                    @forelse ($usuarios as $usuario)
                        <li class="list-group-item">
                            {{ $usuario->nombre }} ({{ $usuario->correo }})
                        </li>
                    @empty
                        <li class="list-group-item">No hay usuarios inscritos aún.</li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>

    @if($puedeGestionar)
        <div class="modal fade" id="agregarMaterialModal" tabindex="-1" aria-labelledby="agregarMaterialModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarMaterialModalLabel">Agregar Material Didáctico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('material.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="curso_id" value="{{ $curso->id }}">

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                            </div>

                            <div class="mb-3">
                                <label for="archivo" class="form-label">Archivo</label>
                                <input type="file" class="form-control" id="archivo" name="archivo" required>
                            </div>

                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="PDF">PDF</option>
                                    <option value="Video">Video</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
