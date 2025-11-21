@extends('layouts.client')


@section('content')
<link href="http://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" ></link>

<style>
    .card{
        max-width: 600px;
        margin: auto;
    }
    .card-header{
        text-align: center;
        font-size: 1.5rem;
        font-weight: bold;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-primary:hover{
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>



<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="text-center">Crear tu Curso</h3>
        </div>
        <div class="card-body">
        <form action="{{ route('cursos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Curso</label>
                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre del Curso" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion del Curso</label>
                <textarea name="descripcion"class="form-control" id="descripcion" rows="3"  placeholder="Descripcion del Curso" required></textarea>
            </div>
            <input type="hidden" name="autor" value="{{$userId}}"> <!-- Cambiado a 'autor' -->
            <div class="mb-3">
                <label for="autor_nombre" class="form-label">Autor</label>
                <input type="text" name="autor_nombre" class="form-control" id="autor_nombre" placeholder="Nombre del Autor" value="{{$userNombre}}" readonly>
            </div>
            <div class="mb-3">
                <label for="categoria_id" class="form-label">Categoría</label>
                <select name="categoria_id" id="categoria_id" class="form-control" required>
                    <option value="">Selecciona una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado del Curso (activo o desactivado)</label>
                <input type="text" name="estado" class="form-control" id="estado" placeholder="Estado del Curso" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" name="precio" class="form-control" id="precio" placeholder="Precio" required>
            </div>
            <div class="mb-3">
                <label for="duracion" class="form-label">Duracion (minutos)</label>
                <input type="number" name="tiempo" class="form-control" id="tiempo" placeholder="Duracion en Minutos" required>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen del Curso</label>
                <input type="file" name="imagen" placeholder="imagen" class="form-control" id="imagen" accept="image/" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Crear Curso</button>
            </div>
          

        </form>
        </div>
    </div>
</div>

@endsection
