@extends('layouts.client')


@section('content')
    <!-- Page content -->
    <section class="container">

        <link rel="stylesheet" href="{{ asset('suscripcion/plan.css') }}">

        @if (Session::has('success'))
            <div class="alert alert-success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif

        {{-- INICIO --}}
        <div class="container">
            <div class="card">
                <h3>Suscripción PRO Mensual</h3>
                <span class="price">50 <span class="currency">USD</span></span>
                <?php $precio = 50; ?>
                <a  class="boton" href="{{ url('stripe', $precio) }}">Suscribirme ahora</a>                            
                <p>Acceso ilimitado a toda la plataforma, cursos, clases en vivo, acceso anticipado a cursos durante el mes
                    de vigencia.</p>
            </div>

            <div class="card">
                <h3>Suscripción PRO Semestral</h3>
                <span class="price">300 <span class="currency">USD</span></span>
                <?php $precio = 300; ?>
                <a class="boton" href="{{ url('stripe', $precio) }}">Suscribirme ahora</a>
                <p>Acceso ilimitado a toda la plataforma, cursos, clases en vivo, acceso anticipado a cursos durante los 6
                    meses de vigencia.</p>
            </div>

            <div class="card highlight">
                <h3>Suscripción PRO Anual</h3>
                <span class="price">700 <span class="currency">USD</span></span>
                <?php $precio = 700; ?>
                <a class="boton" href="{{ url('stripe', $precio) }}">Suscribirme ahora</a>
                <p>Adquiere 1 año entero de suscripción al precio de 11 meses. Acceso ilimitado a toda la plataforma,
                    comunidades, cursos, clases en vivo y más.</p>
            </div>
        </div>

        {{-- FIN --}}



    </section>
@endsection
