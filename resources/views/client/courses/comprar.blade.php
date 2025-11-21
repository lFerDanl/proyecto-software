<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar {{ $curso->nombre }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        body {
            background-color: #f5f7fb;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .panel {
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(82, 67, 170, 0.15);
            border: none;
        }
        .panel-heading {
            background: linear-gradient(135deg, #7c4dff, #5a31d6) !important;
            color: white !important;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }
        .panel-title {
            font-weight: 600;
            font-size: 22px;
            text-align: center;
        }
        .course-summary {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e6e8f2;
            margin-bottom: 25px;
        }
        .course-summary h4 {
            margin-top: 0;
            font-weight: 600;
            color: #4b3fa0;
        }
        .price-tag {
            font-size: 26px;
            font-weight: 700;
            color: #1f9d55;
        }
        .btn-primary {
            background: linear-gradient(135deg, #7c4dff, #5a31d6);
            border-color: #5a31d6;
            padding: 14px;
            font-size: 17px;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #6b3de4, #4825b8);
            border-color: #4825b8;
        }
        .btn-link {
            color: #6b3de4;
            font-weight: 600;
        }
        #card-element {
            padding: 12px;
            border: 1px solid #dfe3f0;
            border-radius: 8px;
            background-color: white;
        }
        .secure-badge {
            text-align: center;
            margin-top: 15px;
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
<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="course-summary">
                <h4>{{ $curso->nombre }}</h4>
                <p class="text-muted">{{ $curso->descripcion }}</p>
                <p class="price-tag">${{ number_format($curso->precio, 2) }} USD</p>
                <p class="text-muted"><i class="glyphicon glyphicon-book"></i> Acceso completo al curso y sus materiales.</p>
            </div>
        </div>
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Información de pago</h3>
                </div>
                <div class="panel-body">
                    @if(empty($stripePublicKey))
                        <div class="alert alert-danger">
                            <strong>Error de configuración:</strong> El sistema de pagos no está disponible en este momento.
                            <p>Contacta al administrador antes de intentar nuevamente.</p>
                        </div>
                    @endif

                    @if ($errors->has('pago'))
                        <div class="alert alert-danger">
                            {{ $errors->first('pago') }}
                        </div>
                    @endif

                    <form role="form" action="{{ route('curso.comprar.procesar', $curso->id) }}" method="post" id="payment-form">
                        @csrf
                        <div class="form-group">
                            <label for="cardholder_name">Nombre del titular</label>
                            <input type="text" class="form-control" id="cardholder_name" name="cardholder_name" placeholder="Ej. Juan Pérez" value="{{ old('cardholder_name', Auth::user()->nombre . ' ' . Auth::user()->apellido) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Tarjeta de crédito o débito</label>
                            <div id="card-element" class="form-control"></div>
                            <small id="card-errors" class="text-danger" role="alert"></small>
                        </div>
                        <div class="form-group" id="system-message" style="display:none;">
                            <div class="alert alert-info">
                                <span id="message-content">Procesando pago, por favor espera...</span>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block" type="submit" {{ empty($stripePublicKey) ? 'disabled' : '' }}>
                            Pagar curso ({{ number_format($curso->precio, 2) }} USD)
                        </button>
                        <a href="{{ route('curso.detalles', $curso->id) }}" class="btn btn-link btn-block">Cancelar y volver al curso</a>
                        <div class="secure-badge">
                            <i class="glyphicon glyphicon-lock"></i> Pago seguro procesado por Stripe
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripeKey = '{{ $stripePublicKey }}';
    if (stripeKey) {
        const stripe = Stripe(stripeKey);
        const elements = stripe.elements();
        const style = {
            base: {
                color: '#32325d',
                fontFamily: 'Poppins, \"Helvetica Neue\", Helvetica, sans-serif',
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
        const card = elements.create('card', { style });
        card.mount('#card-element');

        card.addEventListener('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('system-message').style.display = 'block';
            document.querySelector('button[type="submit"]').disabled = true;

            stripe.createToken(card, {
                name: document.getElementById('cardholder_name').value
            }).then(function(result) {
                if (result.error) {
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                    document.getElementById('system-message').querySelector('.alert').classList.remove('alert-info');
                    document.getElementById('system-message').querySelector('.alert').classList.add('alert-danger');
                    document.getElementById('message-content').textContent = 'Error: ' + result.error.message;
                    document.querySelector('button[type="submit"]').disabled = false;
                } else {
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            const form = document.getElementById('payment-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }
    }
</script>
</body>
</html>
