  <header class="header navbar navbar-expand-lg bg-light navbar-sticky">
    <div class="container px-3">
        <a href="index.html" class="navbar-brand pe-3">
            <img src="{{asset('assets/img/logo.svg')}}" width="47" alt="Silicon">
            Silicon
        </a>
        <div id="navbarNav" class="offcanvas offcanvas-end">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Cursos</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('courses.index')}}" class="dropdown-item">Ver Cursos</a></li>
                            @auth
                            <li><a href="{{route('client.courses.create')}}" class="dropdown-item">Crear Curso</a></li>
                            <li><a href="{{route('mis-cursos')}}" class="dropdown-item">Tus Cursos</a></li>
                            <li><a href="{{ url('compra') }}" class="dropdown-item">Cursos Comprados</a></li>
                            @endauth




                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        {{-- <a href="{{route('suscripcion')}}" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Subscipciones</a> --}}
                        <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Subscipciones</a>

                        <ul class="dropdown-menu">
                            <li><a href="{{route('plan')}}" class="dropdown-item">Subscribirse</a></li>

                            @auth
                            <li><a href="{{route('suscripciones')}}" class="dropdown-item">Tus Subscripciones</a></li>
                            @endauth
                            
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Planes de
                            Estudio</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('plan_estudio.create')}}" class="dropdown-item">Crear Plan de Estudio</a></li>
                            <li><a href="account-details.html" class="dropdown-item">Ver Planes de Estudio</a></li>
                            <li><a href="account-details.html" class="dropdown-item">Tus Planes de Estudio</a></li>
                        </ul>
                    </li>

                    @auth
                    <li class="nav-item">

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Cerrar Sesión
                        </a>
                        {{-- <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Cerrar Sesión
                        </a> --}}
                        {{-- <div class="">
                            <p>Bienvenido {{}}</p>
                        </div> --}}
                    </li>

                        @else
                    <li class="nav-item">
                        <a href="{{route('singin')}}" class="nav-link">Iniciar Sesion</a>
                    </li>
                    @endauth

                </ul>
            </div>

        </div>
        <div class="form-check form-switch mode-switch pe-lg-1 ms-auto me-4" data-bs-toggle="mode">
            <input type="checkbox" class="form-check-input" id="theme-mode">
            <label class="form-check-label d-none d-sm-block" for="theme-mode">Oscuro</label>
            <label class="form-check-label d-none d-sm-block" for="theme-mode">Dark</label>
        </div>
        <button type="button" class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

    </div>
</header>
<!-- Al final de tu archivo, antes de cerrar el body -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
