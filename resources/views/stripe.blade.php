<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Checkout</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .StripeElement {
            box-shadow: 0 1px 3px 0 #e6ebf1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #card-element {
            padding-top: 5px;
        }

        .book-image {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        .checkout-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
<div class="container checkout-wrapper">
    <div class="row w-100">
        <!-- Left: Book Image -->
        <div class="col-md-6 d-flex align-items-center justify-content-center mb-4 mb-md-0">
            <img src="{{ asset('images/img.jpeg') }}" alt="Smart Money Bible" class="book-image">
        </div>

        <!-- Right: Stripe Form -->
        <div class="col-md-6">
            <h4 class="mb-4 fw-bold">Pay with Card</h4>
            <div id="payment-error" class="alert alert-danger d-none"></div>
            <form id="payment-form">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" id="cardholder-email" class="form-control" readonly required value="{{ auth()->user()->email }}">
                </div>
                <div class="mb-3">
                    <label>Card</label>
                    <div id="card-element" class="form-control"></div>
                </div>
                <div class="mb-3">
                    <label>Name on Card</label>
                    <input type="text" id="cardholder-name" class="form-control" placeholder="card holder name" required>
                </div>
                <button id="card-button" class="btn btn-primary w-100" type="submit">Pay $10</button>
            </form>
        </div>
    </div>
</div>

<!-- Stripe & Bootstrap Scripts -->
<script src="https://js.stripe.com/v3/"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.stripe_key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const cardButton = document.getElementById('card-button');
    const cardHolderName = document.getElementById('cardholder-name');
    const cardHolderEmail = document.getElementById('cardholder-email');
    const errorDiv = document.getElementById('payment-error');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        cardButton.disabled = true;
        errorDiv.classList.add('d-none');

        const { clientSecret, error: backendError } = await fetch("{{ route('create.payment.intent') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        }).then(r => r.json());

        if (backendError) {
            errorDiv.innerText = backendError;
            errorDiv.classList.remove('d-none');
            cardButton.disabled = false;
            return;
        }

        const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: cardHolderName.value,
                    email: cardHolderEmail.value
                }
            }
        });

        if (error) {
            errorDiv.innerText = error.message;
            errorDiv.classList.remove('d-none');
            cardButton.disabled = false;
        } else if (paymentIntent.status === 'succeeded') {
            window.location.href = "{{ route('payment.success') }}";
        }
    });
</script>
</body>
</html>
