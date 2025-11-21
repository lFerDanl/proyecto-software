<!DOCTYPE html>
<html>
<head>
    <title>Suscripción - Silicon</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .panel {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: none;
        }
        .panel-heading {
            background-color: #5243AA !important;
            color: white !important;
            border-radius: 8px 8px 0 0 !important;
            padding: 15px;
        }
        .panel-title {
            font-weight: bold;
            font-size: 20px;
            text-align: center;
        }
        .form-control {
            height: 45px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .btn-primary {
            background-color: #5243AA;
            border-color: #4a3d9c;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 4px;
        }
        .btn-primary:hover {
            background-color: #4a3d9c;
            border-color: #42368e;
        }
        .btn-warning {
            background-color: #f8a100;
            border-color: #e89500;
            padding: 12px;
            font-size: 16px;
        }
        #card-element {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
        }
        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .secure-badge {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 14px;
        }
        .secure-badge i {
            color: #28a745;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    
<div class="container">
    <div class="page-header">
        <h1>Completa tu suscripción</h1>
        <p class="lead">Estás a un paso de acceder a todos nuestros cursos</p>
    </div>
    
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                        <h3 class="panel-title">Información de Pago</h3>
                </div>
                <div class="panel-body">
                    @if(empty(env('STRIPE_KEY')))
                        <div class="alert alert-danger">
                            <strong>Error de configuración:</strong> El sistema de pagos no está disponible en este momento.
                            <p>Por favor, inténtelo más tarde o contacte con soporte.</p>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well" style="background-color: #f9f9f9; border-color: #ddd;">
                                <h4 style="margin-top: 0;">Resumen de tu suscripción</h4>
                                <p>Plan seleccionado: <strong>Plan Premium</strong></p>
                                <p>Precio: <strong>{{$precio}} USD</strong></p>
                                <p>Acceso a todos los cursos disponibles en nuestra plataforma.</p>
                            </div>
                        </div>
                    </div>
    
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
   
                    <form 
                            role="form" 
                            action=" {{ route('stripe.post', $precio) }} "
                            method="post" 
                            id="payment-form">
                        @csrf
    
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group card required'>
                                <label class='control-label'>Tarjeta de crédito o débito</label>
                                <!-- Elemento de tarjeta de Stripe -->
                                <div id="card-element" class="form-control" style="height: 40px; padding-top: 10px;"></div>
                                <!-- Mensajes de error de Stripe -->
                                <div id="card-errors" role="alert" class="text-danger" style="margin-top: 10px;"></div>
                            </div>
                        </div>
                        
                        <div class='form-row row'>
                            <div class='col-xs-12'>
                                <p class="text-muted"><small>Ingresa los datos de tu tarjeta para completar la suscripción.</small></p>
                            </div>
                        </div>
    
                        <div class='form-row row'>
                            <div class='col-md-12 error form-group hide'>
                                <div class='alert-danger alert' id='stripe-error-message'>Por favor, corrige los errores e intenta de nuevo.</div>
                            </div>
                        </div>
                        
                        <!-- Mensajes del sistema -->
                        <div class='form-row row' id='system-message' style='display:none;'>
                            <div class='col-md-12'>
                                <div class='alert alert-info'>
                                    <span id='message-content'></span>
                                </div>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Pagar ( {{$precio}} USD) </button>
                                <br>
                                <a href="{{url('/plan')}}" class=" btn btn-warning btn-lg btn-block">Cancelar</a>
                                
                                <div class="secure-badge">
                                    <i class="glyphicon glyphicon-lock"></i> Pago seguro procesado por Stripe
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campo oculto para el token de Stripe -->
                        <input type="hidden" name="payment_method_id" id="payment_method_id">
                            
                    </form>
                </div>
            </div>        
        </div>
    </div>
        
</div>
    
</body>
    
<!-- Cargar Stripe.js v3 (Elements) -->
<script src="https://js.stripe.com/v3/"></script>
<!-- Cargar Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Configurar Stripe Elements -->
<script type="text/javascript">
    // Establecer la clave publicable de Stripe desde el archivo .env
    var stripeKey = '{{ env('STRIPE_KEY') }}';
    console.log('Usando clave publicable de Stripe desde .env:', stripeKey);
    
    // Inicializar Stripe.js v3
    var stripe = Stripe(stripeKey);
    var elements = stripe.elements();
</script>
    
<script type="text/javascript">
  
$(function() {
    // Crear un elemento de tarjeta de Stripe
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    
    // Crear un elemento de tarjeta y montárlo en el DOM
    var card = elements.create('card', {style: style});
    card.mount('#card-element');
    
    // Manejar errores en tiempo real
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Mostrar mensaje de procesamiento
    function showProcessingMessage() {
        $('#system-message').show();
        $('#message-content').text('Procesando su pago, por favor espere...');
    }
    
    // Manejar el envío del formulario
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Mostrar mensaje de procesamiento
        showProcessingMessage();
        
        // Deshabilitar el botón de envío para evitar múltiples envíos
        document.querySelector('button[type="submit"]').disabled = true;
        
        // Crear un token con la información de la tarjeta
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Mostrar el error en el formulario
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                
                // Mostrar mensaje de error
                $('#system-message').show();
                $('#system-message .alert').removeClass('alert-info').addClass('alert-danger');
                $('#message-content').text('Error: ' + result.error.message);
                
                // Habilitar el botón de envío nuevamente
                document.querySelector('button[type="submit"]').disabled = false;
            } else {
                // Enviar el token al servidor
                stripeTokenHandler(result.token);
            }
        });
    });
    
    // Enviar el token al servidor
    function stripeTokenHandler(token) {
        // Insertar el token en el formulario para que se envíe al servidor
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        
        // Actualizar mensaje de procesamiento
        $('#system-message').show();
        $('#system-message .alert').removeClass('alert-danger').addClass('alert-info');
        $('#message-content').text('Procesando su pago, por favor espere...');
        
        // Enviar el formulario
        form.submit();
    }
}); // Fin de $(function())
</script>
</html>