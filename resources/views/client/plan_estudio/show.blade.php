<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan de Estudio Generado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-purple-300 flex justify-center items-center min-h-screen">
    <div class="bg-purple-100 p-8 rounded-xl shadow-lg w-full max-w-md">
        @php
            // Determinar el nivel del plan para mostrar el icono y color adecuados
            $nivelKey = array_key_first($cursos->toArray());
            $nivelInfo = [
                'principiante' => [
                    'icon' => 'fas fa-seedling',
                    'color' => 'green',
                    'text' => 'Principiante'
                ],
                'intermedio' => [
                    'icon' => 'fas fa-book-reader',
                    'color' => 'blue',
                    'text' => 'Intermedio'
                ],
                'avanzado' => [
                    'icon' => 'fas fa-graduation-cap',
                    'color' => 'purple',
                    'text' => 'Avanzado'
                ]
            ];
            
            $currentNivel = $nivelInfo[$nivelKey] ?? $nivelInfo['principiante'];
        @endphp

        <!-- Encabezado con nivel -->
        <div class="flex items-center justify-center mb-6">
            <div class="bg-{{ $currentNivel['color'] }}-100 border border-{{ $currentNivel['color'] }}-300 rounded-full p-3 mr-3">
                <i class="{{ $currentNivel['icon'] }} text-{{ $currentNivel['color'] }}-600 text-xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-purple-900 text-center">
                Plan {{ $currentNivel['text'] }} Generado
            </h1>
        </div>

        <!-- Contenido del plan -->
        <div class="space-y-4 bg-white p-4 rounded-lg shadow-inner">
            @foreach ($cursos as $nivel => $cursosNivel)
                <div>
                    <h2 class="text-{{ $currentNivel['color'] }}-700 font-semibold mb-2 flex items-center">
                        <i class="{{ $currentNivel['icon'] }} mr-2"></i>
                        {{ ucfirst($nivel) }}
                    </h2>
                    <ul class="list-disc list-inside space-y-3">
                        @foreach ($cursosNivel as $curso)
                            <li class="text-gray-800 bg-{{ $currentNivel['color'] }}-50 p-3 rounded-lg border-l-4 border-{{ $currentNivel['color'] }}-400">
                                <span class="font-medium">{{ $curso['nombre'] }}</span>
                                @if ($curso['descripcion'])
                                    <p class="text-sm text-gray-600 mt-1 ml-5">{{ $curso['descripcion'] }}</p>
                                @endif
                                @if ($curso['link'])
                                    <a href="{{ $curso['link'] }}" target="_blank" class="text-blue-500 hover:underline flex items-center mt-2 ml-5 text-sm">
                                        <i class="fas fa-external-link-alt mr-1"></i> Ver recurso
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <!-- Botón de volver -->
        <a href="{{ route('plan_estudio.create') }}" class="block mt-6 text-center bg-{{ $currentNivel['color'] }}-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-{{ $currentNivel['color'] }}-700 transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>
</body>
</html>
