<!DOCTYPE html>
<html>
<head>
    <title>Stripe PaymentIntent Integration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #card-element { height: 40px; padding-top: 10px; }
        .StripeElement { box-shadow: 0 1px 3px 0 #e6ebf1; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h4>Stripe PaymentIntent Demo</h4>

            {{-- Error Message Display --}}
            <div id="payment-error" class="alert alert-danger d-none"></div>

            <form id="payment-form">
                <div class="form-group mb-3">
                    <label>Name:</label>
                    <input class="form-control" type="text" id="cardholder-name" required>
                </div>
                <div class="form-group mb-3">
                    <label>Card:</label>
                    <div id="card-element" class="form-control"></div>
                </div>
                <button id="card-button" class="btn btn-success w-100" type="submit">Pay $10</button>
            </form>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.stripe_key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('cardholder-name');
    const cardButton = document.getElementById('card-button');
    const form = document.getElementById('payment-form');
    const errorDiv = document.getElementById('payment-error');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        cardButton.disabled = true;
        errorDiv.classList.add('d-none');

        const {clientSecret, error: backendError} = await fetch("{{ route('create.payment.intent') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        }).then(r => r.json());

        if (backendError) {
            errorDiv.classList.remove('d-none');
            errorDiv.innerText = backendError;
            cardButton.disabled = false;
            return;
        }

        const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: cardHolderName.value
                }
            }
        });

        if (error) {
            errorDiv.classList.remove('d-none');
            errorDiv.innerText = error.message;
            cardButton.disabled = false;
        } else if (paymentIntent.status === 'succeeded') {
            // ✅ Redirect to dashboard with success
            fetch("{{ route('payment.success') }}")
                .then(() => window.location.href = "{{ route('dashboard') }}");
        }
    });
</script>
</body>
</html>
