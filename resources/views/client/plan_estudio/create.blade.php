<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Plan de Estudio</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        <!-- Formulario -->
        <form method="POST" action="{{ route('plan_estudio.generar') }}">
            @csrf

            <!-- Dropdown para áreas de estudio -->
            <div class="mb-6">
                <label for="area_estudio" class="block text-purple-800 font-medium mb-2">Área de Estudio</label>
                <select id="area_estudio" name="area_estudio" class="w-full border-purple-300 bg-purple-50 text-purple-900 rounded-lg shadow-sm focus:ring focus:ring-purple-400 focus:outline-none focus:border-purple-500 transition px-4 py-2" required>
                    <option value="" disabled selected>Selecciona un área</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botón de guardar -->
            <button type="submit" class="w-full bg-purple-600 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:bg-purple-700 focus:ring focus:ring-purple-400 focus:outline-none transition">
                Generar
            </button>
        </form>
    </div>
</body>
</html>
