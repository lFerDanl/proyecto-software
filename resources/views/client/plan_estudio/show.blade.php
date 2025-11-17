<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan de Estudio Generado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-purple-300 flex justify-center items-center min-h-screen">
    <div class="bg-purple-100 p-8 rounded-xl shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold text-purple-900 text-center mb-6">Plan de Estudio Generado</h1>

        <div class="space-y-4">
            @foreach ($cursos as $nivel => $cursosNivel)
                <div>
                    <h2 class="text-purple-800 font-semibold mb-2">{{ ucfirst($nivel) }}</h2>
                    <ul class="list-disc list-inside space-y-2">
                        @foreach ($cursosNivel as $curso)
                            <li class="text-purple-700">
                                {{ $curso['nombre'] }}
                                @if ($curso['descripcion'])
                                    <p class="text-sm text-gray-600">{{ $curso['descripcion'] }}</p>
                                @endif
                                @if ($curso['link'])
                                    <a href="{{ $curso['link'] }}" target="_blank" class="text-blue-500 hover:underline">Ver video</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <a href="{{ route('plan_estudio.create') }}" class="block mt-6 text-center bg-purple-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-purple-700">
            Volver
        </a>
    </div>
</body>
</html>
