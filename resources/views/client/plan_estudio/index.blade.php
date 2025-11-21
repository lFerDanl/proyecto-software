@extends('layouts.client')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-primary mb-1">Tus Planes de Estudio</h1>
            <p class="text-muted mb-0">Revisa los planes generados para cada curso y nivel.</p>
        </div>
        <a href="{{ route('plan_estudio.create') }}" class="btn btn-primary">
            <i class="bi bi-magic"></i> Crear nuevo plan
        </a>
    </div>

    @if($planes->isEmpty())
        <div class="alert alert-info">
            Aún no has generado planes de estudio. ¡Comienza creando uno desde la opción "Crear Plan de Estudio"!
        </div>
    @else
        @php
            $planesPorCurso = $planes->groupBy('curso_id');
        @endphp

        <div class="accordion" id="planesAccordion">
            @foreach($planesPorCurso as $cursoId => $planesCurso)
                @php
                    $curso = optional($planesCurso->first()->curso);
                    $headingId = 'heading-'.$cursoId;
                    $collapseId = 'collapse-'.$cursoId;
                @endphp
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="{{ $headingId }}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                            <div>
                                <strong>{{ $curso->nombre ?? 'Curso eliminado' }}</strong>
                                <p class="mb-0 text-muted small">{{ $planesCurso->count() }} plan(es) generados</p>
                            </div>
                        </button>
                    </h2>
                    <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="{{ $headingId }}" data-bs-parent="#planesAccordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nombre del plan</th>
                                            <th>Nivel</th>
                                            <th>Generado el</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($planesCurso as $plan)
                                            <tr>
                                                <td>{{ $plan->nombre }}</td>
                                                <td>
                                                    @php
                                                        $badgeClasses = match($plan->nivel) {
                                                            'intermedio' => 'bg-info text-dark',
                                                            'avanzado' => 'bg-danger text-white',
                                                            default => 'bg-success text-white',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClasses }} text-uppercase fw-semibold">
                                                        {{ ucfirst($plan->nivel) }}
                                                    </span>
                                                </td>
                                                <td>{{ $plan->created_at?->format('d/m/Y H:i') }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('plan_estudio.show', $plan) }}" class="btn btn-outline-primary btn-sm">
                                                        Ver plan
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
