@extends('layouts.client')


@section('content')


    @if (Session::has('success'))
        <div class="alert alert-success text-center">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <p>{{ Session::get('success') }}</p>
        </div>
    @endif


    @if (Session::has('error'))
        <div class="alert alert-danger text-center">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <p>{{ Session::get('error') }}</p>
        </div>
    @endif


    <!-- Page content -->
    <section class="container">

        <!-- Breadcrumb -->
        <nav class="pt-4 mt-lg-3" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="landing-online-courses.html"><i class="bx bx-home-alt fs-lg me-1"></i>Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Courses</li>
            </ol>
        </nav>



        <!-- Page title + Filters -->
        <div class="d-lg-flex align-items-center justify-content-between py-4 mt-lg-2">
            <h1 class="me-3">Courses</h1>
            <div class="d-md-flex mb-3">
                <select class="form-select me-md-4 mb-2 mb-md-0" style="min-width: 240px;">
                    <option value="All">All categories</option>
                    <option value="Web Development">Web Development</option>
                    <option value="Mobile Development">Mobile Development</option>
                    <option value="Programming">Programming</option>
                    <option value="Game Development">Game Development</option>
                    <option value="Software Testing">Software Testing</option>
                    <option value="Software Engineering">Software Engineering</option>
                    <option value="Network & Security">Network &amp; Security</option>
                </select>
                <div class="position-relative" style="min-width: 300px;">
                    <input type="text" class="form-control pe-5" placeholder="Search courses">
                    <i class="bx bx-search text-nav fs-lg position-absolute top-50 end-0 translate-middle-y me-3"></i>
                </div>
            </div>
        </div>


        <!-- Courses grid -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 gx-3 gx-md-4 mt-n2 mt-sm-0">

            <!-- Aquí asume que has pasado la variable $cursos desde el controlador -->
            @foreach ($cursos as $curso)
                <div class="col pb-1 pb-lg-3 mb-4 d-sm-none d-lg-block">
                    <article class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <a href="{{ route('curso.detalle', $curso->id) }}"
                                class="d-block position-absolute w-100 h-100 top-0 start-0"></a>
                            <a href="#"
                                class="btn btn-icon btn-light bg-white border-white btn-sm rounded-circle position-absolute top-0 end-0 zindex-2 me-3 mt-3"
                                data-bs-toggle="tooltip" data-bs-placement="left" title="Save to Favorites">
                                <i class="bx bx-bookmark"></i>
                            </a>
                            <img src="{{ $curso->imagen ? asset($curso->imagen) : 'assets/img/portfolio/courses/default.jpg' }}"
                                class="card-img-top" alt="{{ $curso->nombre }}">
                        </div>
                        <div class="card-body pb-3">
                            <h3 class="h5 mb-2">
                                <a href="{{ route('curso.detalle', $curso->id) }}">{{ $curso->nombre }}</a>
                            </h3>
                            <p class="fs-sm mb-2">By {{ $curso->autornombre->nombre ?? 'Unknown Author' }}</p>
                            <p class="fs-sm mb-2"> {{ $curso->descripcion ?? 'No tiene Descripcion' }}</p>
                            <p class="fs-lg fw-semibold text-primary mb-0">${{ number_format($curso->precio, 2) }}</p>
                        </div>
                        <div class="card-footer d-flex align-items-center fs-sm text-muted py-4">
                            <div class="d-flex align-items-center me-4">
                                <i class="bx bx-time fs-xl me-1"></i>
                                {{ $curso->tiempo }} hours
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-like fs-xl me-1"></i>
                                {{ $curso->calificacion }}% ({{ number_format($curso->calificacion / 10, 1) }}K)
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach

            <!-- Pagination: Basic example -->
            <nav class="pb-5" aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a href="#" class="page-link">
                            <i class="bx bx-chevron-left ms-n1 me-1"></i>
                            Prev
                        </a>
                    </li>
                    <li class="page-item disabled d-sm-none">
                        <span class="page-link text-body">1 / 4</span>
                    </li>
                    <li class="page-item active d-none d-sm-block" aria-current="page">
                        <span class="page-link">
                            1
                            <span class="visually-hidden">(current)</span>
                        </span>
                    </li>
                    <li class="page-item d-none d-sm-block">
                        <a href="#" class="page-link">2</a>
                    </li>
                    <li class="page-item d-none d-sm-block">
                        <a href="#" class="page-link">3</a>
                    </li>
                    <li class="page-item d-none d-sm-block">
                        <a href="#" class="page-link">4</a>
                    </li>
                    <li class="page-item">
                        <a href="#" class="page-link">
                            Next
                            <i class="bx bx-chevron-right me-n1 ms-1"></i>
                        </a>
                    </li>
                </ul>
            </nav>



    </section>
@endsection
