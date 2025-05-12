<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Widget</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-dark {
            background: #000;
        }

        .side-donate {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 30%;
            text-align: center;
            color: #fff;
        }

        .donate-btn {
            background: #d4a017;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
        }

        .donation-widget {
            max-width: 400px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .widget-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #f8f8f8;
        }

        .widget-header h2 {
            color: #d4a017;
            margin: 0;
            font-size: 1.5rem;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .donation-type h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .donation-toggle button {
            background: #f0f0f0;
            border: 1px solid #ddd;
            padding: 5px 15px;
            margin-right: 5px;
        }

        .donation-toggle .active {
            background: #d4a017;
            color: #fff;
        }

        .amount-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 1rem;
        }

        .amount-btn {
            background: #f0f0f0;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .amount-btn.active {
            background: #d4a017;
            color: #fff;
        }

        .continue-btn,
        .finish-btn {
            background: #d4a017;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
        }

        .back-btn {
            background: none;
            border: none;
            color: #d4a017;
        }

        .donation-summary {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .payment-method .btn-payment {
            background: #f0f0f0;
            border: 1px solid #ddd;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
        }

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #d4a017;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .side-donate {
                display: none;
            }

            .donation-widget {
                margin: 1rem;
            }

            .amount-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <div class="ms-auto">
                <a href="#" class="nav-link text-white">About</a>
                <a href="#" class="nav-link text-white">Sign In</a>
            </div>
        </div>
    </nav>

    <div class="container position-relative">
        <div class="side-donate">
            <button class="donate-btn" data-bs-toggle="modal" data-bs-target="#donationModal">
                Donate <span class="arrow">→</span>
            </button>
            <p class="donate-text">
                Make donating to your favorite causes an enjoyable experience with thousands of beautiful people
                spreading the love and making it easier to find, fund, and resource missions
            </p>
        </div>



        <!-- Modal for Donation Widget -->
        <div class="modal fade" id="donationModal" tabindex="-1" aria-labelledby="donationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="donation-widget" id="step1">
                        <div class="widget-header">
                            <h2>DONATE</h2>
                            <button class="close-btn" data-bs-dismiss="modal">×</button>
                        </div>

                        <div class="widget-content">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <form method="POST" action="{{ route('donation.process') }}" id="donationForm">
                                @csrf
                                <div class="donation-type mb-4">
                                    <h3>Missionary Donation</h3>
                                    <div class="donation-toggle">
                                        <button type="button" class="active" data-type="one-time">One-Time</button>
                                        <button type="button" data-type="monthly">Monthly</button>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="donor_name"
                                                placeholder="Donor's Name" value="{{ old('donor_name') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="email" class="form-control" name="email"
                                                placeholder="Donor's Email" value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <input type="text" class="form-control mb-4" name="night_bright"
                                    placeholder="Night Bright" value="{{ old('night_bright') }}">

                                <div class="amount-grid">
                                    <button type="button" class="amount-btn" data-amount="10">$10</button>
                                    <button type="button" class="amount-btn" data-amount="25">$25</button>
                                    <button type="button" class="amount-btn" data-amount="50">$50</button>
                                    <button type="button" class="amount-btn" data-amount="100">$100</button>
                                    <button type="button" class="amount-btn" data-amount="250">$250</button>
                                    <button type="button" class="amount-btn" data-amount="500">$500</button>
                                    <button type="button" class="amount-btn" data-amount="1000">$1000</button>
                                    <input type="number" class="form-control amount-btn" name="amount"
                                        id="customAmountInput" placeholder="Other" min="1"
                                        value="{{ old('amount') }}">
                                </div>

                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="anonymous" id="anonymousCheck"
                                        value="1" {{ old('anonymous') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="anonymousCheck">
                                        Stay Anonymous
                                    </label>
                                </div>

                                <input type="hidden" name="donation_type" id="donationTypeInput" value="one-time">
                                <input type="hidden" name="selected_amount" id="selectedAmountInput">
                                <input type="hidden" name="payment_method" id="paymentMethodInput">
                                <button type="button" class="continue-btn" id="nextBtn">Continue</button>
                            </form>
                        </div>
                    </div>

                    <div class="donation-widget" id="step2" style="display: none;">
                        <div class="widget-header">
                            <h2>DONATE</h2>
                            <button class="close-btn" data-bs-dismiss="modal">×</button>
                        </div>

                        <div class="widget-content">
                            <div class="back-header">
                                <button type="button" class="back-btn" id="backBtn">← Final Details</button>
                            </div>

                            <div class="donation-summary">
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Donation</span>
                                    <span id="summaryAmount">$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Credit card processing fees</span>
                                    <span id="summaryFees">$0.00</span>
                                </div>
                            </div>

                            <div class="payment-method mb-4">
                                <div class="dropdown">
                                    <button class="btn btn-payment dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" id="paymentMethodBtn">
                                        Visa & Others
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><button class="dropdown-item" type="button" data-method="amex">AMEX
                                                Card</button></li>
                                        <li><button class="dropdown-item" type="button" data-method="visa">Visa &
                                                Others</button></li>
                                        <li><button class="dropdown-item" type="button" data-method="us_bank">US
                                                Bank
                                                Account</button></li>
                                        <li><button class="dropdown-item" type="button" data-method="cash_app">Cash
                                                App
                                                Pay</button></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <small>Why? Night Bright now charges platform fees that take on your generosity to
                                    support
                                    this free service</small>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="contact" id="contactCheck"
                                    {{ old('contact') ? 'checked' : '' }}>
                                <label class="form-check-label" for="contactCheck">
                                    Allow Night Bright to contact me
                                </label>
                            </div>

                            <button type="submit" class="finish-btn" form="donationForm">Finish ($0.00)</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>



    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-spinner"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // DOM elements
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const nextBtn = document.getElementById('nextBtn');
        const backBtn = document.getElementById('backBtn');
        const amountButtons = document.querySelectorAll('#step1 .amount-btn');
        const customAmountInput = document.getElementById('customAmountInput');
        const summaryAmount = document.getElementById('summaryAmount');
        const summaryFees = document.getElementById('summaryFees');
        const selectedAmountInput = document.getElementById('selectedAmountInput');
        const paymentMethodBtn = document.getElementById('paymentMethodBtn');
        const paymentMethodInput = document.getElementById('paymentMethodInput');
        const donationTypeInput = document.getElementById('donationTypeInput');
        const loadingScreen = document.getElementById('loadingScreen');
        const donationForm = document.getElementById('donationForm');
        let selectedAmount = 0;

        // Initialize default values
        function initialize() {
            paymentMethodInput.value = 'visa';
            paymentMethodBtn.textContent = 'Visa & Others';
            if (customAmountInput.value) {
                selectedAmount = parseFloat(customAmountInput.value) || 0;
            }
            updateSummary();
        }

        // Update donation summary
        function updateSummary() {
            const fees = selectedAmount * 0.029 + 0.30; // Approx Stripe fees
            summaryAmount.textContent = `$${selectedAmount.toFixed(2)}`;
            summaryFees.textContent = `$${fees.toFixed(2)}`;
            selectedAmountInput.value = selectedAmount;
            document.querySelector('.finish-btn').textContent = `Finish ($${ (selectedAmount + fees).toFixed(2) })`;
        }

        // Amount selection
        amountButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                amountButtons.forEach(b => b.classList.remove('active'));
                if (btn.tagName === 'BUTTON') {
                    btn.classList.add('active');
                    selectedAmount = parseFloat(btn.dataset.amount);
                    customAmountInput.value = selectedAmount;
                }
                updateSummary();
            });
        });

        // Custom amount input
        customAmountInput.addEventListener('input', () => {
            selectedAmount = parseFloat(customAmountInput.value) || 0;
            amountButtons.forEach(b => b.classList.remove('active'));
            updateSummary();
        });

        // Toggle donation type
        document.querySelectorAll('.donation-toggle button').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelector('.donation-toggle .active').classList.remove('active');
                btn.classList.add('active');
                donationTypeInput.value = btn.dataset.type || 'one-time';
            });
        });

        // Payment method selection
        document.querySelectorAll('.dropdown-menu .dropdown-item').forEach(item => {
            item.addEventListener('click', () => {
                paymentMethodBtn.textContent = item.textContent;
                paymentMethodInput.value = item.dataset.method;
            });
        });

        // Step navigation
        nextBtn.addEventListener('click', () => {
            step1.style.display = 'none';
            step2.style.display = 'block';
            updateSummary();
        });

        backBtn.addEventListener('click', () => {
            step2.style.display = 'none';
            step1.style.display = 'block';
        });

        // Show loading screen on form submit
        donationForm.addEventListener('submit', () => {
            loadingScreen.style.display = 'flex';
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initialize);
    </script>
</body>

</html>
