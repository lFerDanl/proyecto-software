<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS para el Sidebar -->
    <style>
        /* Sidebar styling */
        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            transition: all 0.3s;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar .nav-link {
            padding: 15px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
            width: 100%;
        }
        /* Styles when sidebar is hidden */
        .sidebar-hidden .sidebar {
            margin-left: -250px;
        }
        .sidebar-hidden .content {
            margin-left: 0;
        }
    </style>
</head>
<body class="sidebar-hidden">

    <!-- Sidebar -->
    <div class="sidebar p-3" id="sidebar">
        <h4>Admin Panel</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.secciones.usuarios') }}">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.secciones.renovaciones') }}">renovaciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.secciones.subscripciones') }}">subcripciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/categorias') }}">Gestionar Categoria</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.secciones.CursoCrud') }}">Gestion de Cursos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/bitacora') }}">Preferencias de Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/estadistica') }}">Estadistica</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/bitacora') }}">Bitacora</a>
            </li>

            <!-- Botón de cerrar sesión -->
            <li class="nav-item">
                <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Cerrar Sesión
                </a>
            </li>
        </ul>

        <!-- Formulario oculto para cerrar sesión -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="content" id="content">
        <!-- Botón para mostrar/ocultar sidebar en pantallas pequeñas -->
        <button class="btn btn-primary mb-3" onclick="toggleSidebar()">☰ Menú</button>

        <!-- Área de contenido -->
        @yield('content')
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS para toggle del Sidebar -->
    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-hidden');
        }
    </script>
</body>
</html>
