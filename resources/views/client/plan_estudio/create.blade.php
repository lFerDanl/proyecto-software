<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Plan de Estudio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-purple-300 flex justify-center items-center min-h-screen">
    <div class="bg-purple-100 p-8 rounded-xl shadow-lg w-full max-w-md transform transition hover:scale-105 duration-200">
        <!-- Título -->
        <h1 class="text-2xl font-bold text-purple-900 text-center mb-6 border-b border-purple-300 pb-4">Crear Plan de Estudio</h1>

        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
                <p class="font-semibold">{{ $errors->first() }}</p>
            </div>
        @endif

        <!-- La variable tieneSuscripcionActiva ahora viene del controlador -->

        <!-- Mensaje informativo sobre la suscripción activa -->
        @if($tieneSuscripcionActiva)
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
                <h3 class="font-bold"><i class="fas fa-star mr-1"></i> Suscripción Activa</h3>
                <p>Gracias a tu suscripción activa, puedes generar planes de estudio para todos los cursos disponibles.</p>
                <p class="text-xs mt-1">Cursos disponibles: {{ $cursosComprados->count() }}</p>
            </div>
        @endif

        <!-- Dropdown para seleccionar curso -->
        <div class="mb-6">
            <label for="curso_id" class="block text-purple-800 font-medium mb-2">
                @if($tieneSuscripcionActiva)
                    Selecciona un curso
                @else
                    Curso comprado
                @endif
            </label>
            
            @if($cursosComprados->isEmpty())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
                    <h3 class="font-bold"><i class="fas fa-exclamation-circle mr-1"></i> No hay cursos disponibles</h3>
                    <p>No se encontraron cursos disponibles en el sistema.</p>
                    <p class="mt-2">
                        <a href="{{ route('courses.index') }}" class="text-blue-600 hover:underline">
                            <i class="fas fa-search mr-1"></i> Explorar catálogo de cursos
                        </a>
                    </p>
                </div>
                <!-- Campo oculto para evitar errores de validación -->
                <select id="curso_id" name="curso_id" class="hidden" required>
                    <option value="" disabled selected>No hay cursos disponibles</option>
                </select>
            @else
                <select id="curso_id" name="curso_id" class="w-full border-purple-300 bg-purple-50 text-purple-900 rounded-lg shadow-sm focus:ring focus:ring-purple-400 focus:outline-none focus:border-purple-500 transition px-4 py-2" required>
                    <option value="" disabled selected>Selecciona un curso</option>
                    @foreach ($cursosComprados as $curso)
                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                    @endforeach
                </select>
                <p class="text-gray-500 text-xs mt-1">{{ $cursosComprados->count() }} curso(s) disponible(s)</p>
            @endif
        </div>

        <!-- Opciones de planes según suscripción -->
        <div class="space-y-4 mb-6">
            <h2 class="text-lg font-semibold text-purple-800">Selecciona el nivel del plan:</h2>
            
            <!-- Plan Principiante (disponible para todos) -->
            <form method="POST" action="{{ route('plan_estudio.generar.principiante') }}" id="form-principiante" class="mb-3">
                @csrf
                <input type="hidden" name="nivel" value="principiante">
                <input type="hidden" name="curso_id" id="curso-principiante">
                <button type="button" onclick="submitForm('principiante')" 
                    class="w-full {{ $cursosComprados->isEmpty() ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700' }} text-white font-semibold py-3 px-4 rounded-lg shadow-md focus:ring focus:ring-green-400 focus:outline-none transition flex items-center justify-center"
                    {{ $cursosComprados->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-seedling mr-2"></i> Generar Plan Principiante
                </button>
            </form>
            
            <!-- Planes disponibles solo con suscripción activa -->
            @if($tieneSuscripcionActiva)
                <!-- Plan Intermedio -->
                <form method="POST" action="{{ route('plan_estudio.generar.intermedio') }}" id="form-intermedio" class="mb-3">
                    @csrf
                    <input type="hidden" name="nivel" value="intermedio">
                    <input type="hidden" name="curso_id" id="curso-intermedio">
                    <button type="button" onclick="submitForm('intermedio')" 
                        class="w-full {{ $cursosComprados->isEmpty() ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-semibold py-3 px-4 rounded-lg shadow-md focus:ring focus:ring-blue-400 focus:outline-none transition flex items-center justify-center"
                        {{ $cursosComprados->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-book-reader mr-2"></i> Generar Plan Intermedio
                    </button>
                </form>
                
                <!-- Plan Avanzado -->
                <form method="POST" action="{{ route('plan_estudio.generar.avanzado') }}" id="form-avanzado">
                    @csrf
                    <input type="hidden" name="nivel" value="avanzado">
                    <input type="hidden" name="curso_id" id="curso-avanzado">
                    <button type="button" onclick="submitForm('avanzado')" 
                        class="w-full {{ $cursosComprados->isEmpty() ? 'bg-gray-400 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-700' }} text-white font-semibold py-3 px-4 rounded-lg shadow-md focus:ring focus:ring-purple-400 focus:outline-none transition flex items-center justify-center"
                        {{ $cursosComprados->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-graduation-cap mr-2"></i> Generar Plan Avanzado
                    </button>
                </form>
            @else
                <!-- Mensaje para usuarios sin suscripción -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mt-4">
                    <h3 class="font-bold">Planes adicionales disponibles</h3>
                    <p class="text-sm">Obtén una suscripción para acceder a planes de estudio Intermedios y Avanzados.</p>
                    <a href="{{ route('plan') }}" class="text-blue-600 hover:underline text-sm block mt-2">Ver planes de suscripción</a>
                </div>
            @endif
        </div>
    </div>

    <script>
        function submitForm(nivel) {
            // Verificar si el botón está deshabilitado
            const button = document.querySelector(`#form-${nivel} button`);
            if (button.disabled || button.classList.contains('cursor-not-allowed')) {
                alert('No hay cursos disponibles para generar un plan de estudio.');
                return;
            }
            
            // Obtener el valor seleccionado del curso
            const cursoSelect = document.getElementById('curso_id');
            if (cursoSelect.value === '') {
                alert('Por favor, selecciona un curso primero');
                return;
            }
            
            // Asignar el valor del curso al formulario correspondiente
            document.getElementById('curso-' + nivel).value = cursoSelect.value;
            
            // Mostrar indicador de carga
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generando...';
            button.disabled = true;
            
            // Enviar el formulario
            document.getElementById('form-' + nivel).submit();
        }
    </script>
</body>
</html>
