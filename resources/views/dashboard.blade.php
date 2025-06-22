<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            background-color: #eee; /* Moved here from the div */
        }
        body {
            padding-top: 56px; /* To account for fixed navbar */
        }

        .navbar-container {
            padding-left: 20px;
            padding-right: 20px;
        }

        .alert-success {
            margin: 20px;
            margin-top: 30px;
        }

        .dropdown-menu {
            min-width: 200px;
        }

        .user-dropdown {
            padding-right: 15px;
        }

        @media (max-width: 767.98px) {
            .border-sm-start-none {
                border-left: none !important;
            }
        }
    </style>
</head>
<body>

{{--<div style="background-color: #eee;">--}}
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid navbar-container">
        <a class="navbar-brand ms-3" href="#">Course Point</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown user-dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                       role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <span class="user-name">{{auth()->user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<section>
    <div class="container py-5">
        <div class="row justify-content-center mb-3">
            <div class="col-md-12 col-xl-10">
                <div class="card shadow-0 border rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                                <div class="bg-image hover-zoom ripple rounded ripple-surface">
                                    <img
                                        src="https://mdbcdn.b-cdn.net/img/Photos/Horizontal/E-commerce/Products/img%20(4).webp"
                                        class="w-100"/>
                                    <a href="#!">
                                        <div class="hover-overlay">
                                            <div class="mask"
                                                 style="background-color: rgba(253, 253, 253, 0.15);"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xl-6">
                                <h5>Quant trident shirts</h5>
                                <div class="d-flex flex-row">
                                    <div class="text-danger mb-1 me-2">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <span>310</span>
                                </div>
                                <div class="mt-1 mb-0 text-muted small">
                                    <span>100% cotton</span>
                                    <span class="text-primary"> • </span>
                                    <span>Light weight</span>
                                    <span class="text-primary"> • </span>
                                    <span>Best finish<br/></span>
                                </div>
                                <div class="mb-2 text-muted small">
                                    <span>Unique design</span>
                                    <span class="text-primary"> • </span>
                                    <span>For men</span>
                                    <span class="text-primary"> • </span>
                                    <span>Casual<br/></span>
                                </div>
                                <p class="description text-truncate mb-4 mb-md-0">
                                    There are many variations of passages of Lorem Ipsum available, but the
                                    majority have suffered alteration in some form, by injected humour, or
                                    randomised words which don't look even slightly believable.
                                </p>
                            </div>
                            <div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                                <div class="d-flex flex-row align-items-center mb-1">
                                    <h4 class="mb-1 me-1">$13.99</h4>
                                    <span class="text-danger"><s>$20.99</s></span>
                                </div>
                                <div class="d-flex flex-column mt-4">
                                    <button class="btn btn-primary btn-sm toggle-description" type="button">Details</button>

                                    <button class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#stripePaymentModal">
                                        Buy Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stripe Payment Modal -->
<div class="modal fade" id="stripePaymentModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="stripePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="payment-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="stripePaymentModalLabel">Pay with Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div id="payment-error" class="alert alert-danger d-none"></div>

                    <div class="form-group mb-3">
                        <label for="cardholder-name" class="fw-bold">Name</label>
                        <input class="form-control" type="text" id="cardholder-name" placeholder="card holder name" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Card Details</label>
                        <div id="card-element" class="form-control"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="card-button" class="btn btn-primary w-100" type="submit">Pay $10</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };

    @if(session('success'))
    toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
    toastr.error("{{ session('error') }}");
    @endif

    @if ($errors->any())
    toastr.error("{{ $errors->first() }}");
    @endif

    document.querySelectorAll('.toggle-description').forEach(function(button) {
        button.addEventListener('click', function() {
            const description = this.closest('.row').querySelector('.description');
            description.classList.toggle('text-truncate');

            // Optionally change button text
            if (description.classList.contains('text-truncate')) {
                this.textContent = 'Details';
            } else {
                this.textContent = 'Show less';
            }
        });
    });

    const stripe = Stripe("{{ config('services.stripe.stripe_key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const cardButton = document.getElementById('card-button');
    const cardHolderName = document.getElementById('cardholder-name');
    const errorDiv = document.getElementById('payment-error');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        cardButton.disabled = true;
        errorDiv.classList.add('d-none');

        {{--const {clientSecret, error: backendError} = await fetch("{{ route('create.payment.intent') }}", {--}}
        {{--    method: "POST",--}}
        {{--    headers: {--}}
        {{--        "Content-Type": "application/json",--}}
        {{--        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')--}}
        {{--    },--}}
        {{--    body: JSON.stringify({})--}}
        {{--}).then(r => r.json());--}}

        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        const response = await fetch("{{ route('create.payment.intent') }}", {
            method: "POST",
            body: formData
        });

        const { clientSecret, error: backendError } = await response.json();


        if (backendError) {
            errorDiv.textContent = backendError;
            errorDiv.classList.remove('d-none');
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
            errorDiv.textContent = error.message;
            errorDiv.classList.remove('d-none');
            cardButton.disabled = false;
        } else if (paymentIntent.status === 'succeeded') {
            fetch("{{ route('payment.success') }}")
                .then(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('stripePaymentModal'));
                    modal.hide();
                    window.location.href = "{{ route('dashboard') }}";
                });
        }
    });
</script>


</body>
</html>
