<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Night Bright</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/assets/css/style.css">
    
</head>

<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <img src="/assets/images/name.png" alt="">
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav>
                <a href="#">Find</a>
                <a href="#">Become</a>
                <a href="#">Forum</a>
                <a href="#">About</a>
                <a href="#">Sign In</a>
            </nav>
        </div>
    </header>

    <section class="content">

        <div class="container " style="max-width: 800px; margin-bottom:3%">

            < Back</div>


                <div class="container bg-white p-5 shadow" style="max-width: 800px; height:500px; border-radius: 15px">
                    <div class="row align-items-center justify-content-between">
                        <!-- Merged Left + Center -->
                        <div class="col-md-8 d-flex align-items-center gap-4">
                            <img src="/assets/images/logo.png" alt="Night Bright Logo" class="logo-img">

                            <div class="info">
                                <h1 class="h4 mb-2">Night Bright</h1>

                                <div class="tags d-flex flex-column">
                                    <span class="badge text-dark mb-2">North America</span>
                                    <span class="badge text-dark">United States</span>
                                </div>

                            </div>
                        </div>

                        <!-- Right: Donate button -->
                        <div class="col-md-4 text-end">
                            <button class="donate-btn rounded-pill fw-semibold">
                                Donate <span class="border border-white rounded-circle px-2">➔</span>
                            </button>
                        </div>
                    </div>


                    <hr class="mt-4">


                    <div class="description">
                        Night Bright is a non-profit 501(c)3. We strive to make donating to your favorite causes an
                        enjoyable experience that leads to a deeper connection with the thousands of beautiful people
                        spreading the love of God throughout the globe. Please join us in making it easier to find,
                        fund, and resource missions worldwide.
                    </div>

                </div>


                {{-- <div class="card">
                    <img src="https://static.wixstatic.com/media/fc3924_016887e8…_0.01,enc_avif,quality_auto/Image-empty-state.png"
                        alt="Night Bright Logo" class="logo-img">
                    <div class="info">
                        <h1>Night Bright</h1>
                        <div class="tags">
                            <span>North America</span>
                            <span>United States</span>
                        </div>
                    </div>
                    <button class="donate-btn">Donate <span>➔</span></button>
                </div> --}}




                <div class="overlay" id="donateOverlay">
                    <div class="overlay-header">
                        <h4 style="color: #bd944d;">Donate</h4>
                        <button class="close-btn" onclick="closeOverlay()">×</button>
                    </div>

                    <div class="overlay-content " style="background-color: white;">
                        <div id="errorMessage" style="color: red; margin-bottom: 1em; display: none; text-align:center"></div>

                        <form id="donationForm" onsubmit="submitToStripe(); return false;">
                            <!-- STEP 1 -->
                            <div class="donation-form" id="step1">
                                <h5 style="height: 20px; font-size: 15px;"> <strong>Missionary Donation</strong></h5>
                                <hr class="mx-n4">

                                <div class="tab-selector">
                                    <button type="button" class="active"
                                        onclick="selectTab(this, 'one-time')">One-Time</button>
                                    <button type="button" onclick="selectTab(this, 'monthly')">Monthly</button>
                                </div>

                                <input type="hidden" name="donation_type" id="donation_type" value="one-time">

                                <div class="donor-info">
                                    <input type="text" name="donor_name" placeholder="Donor's Name" required>
                                    <input type="email" name="email" placeholder="Donor's Email" required>
                                </div>


                                <input style="width: 100%; font-weight:normal" class="text-muted" type="text"
                                    name="night_bright" value="Night Bright" placeholder="Night Bright">



                                <div class="amount-buttons" id="amountButtons" style="font-size: 12px">
                                    <button type="button" onclick="selectAmount(this)">10$</button>
                                    <button type="button" onclick="selectAmount(this)">25$</button>
                                    <button type="button" onclick="selectAmount(this)">50$</button>
                                    <button type="button" onclick="selectAmount(this)">100$</button>
                                    <button type="button" onclick="selectAmount(this)">250$</button>
                                    <button type="button" onclick="selectAmount(this)">500$</button>
                                    <button type="button" onclick="selectAmount(this)">1000$</button>
                                    <button type="button" onclick="selectAmount(this)">Other</button>
                                </div>

                                <input type="number" name="selected_amount" id="selectedAmount"
                                    placeholder="Other amount" class="form-control mt-2"
                                    style="display: none; max-width: 200px;">

                                <div class="options">
                                    <a href="#" class="text-decoration-none" style="color: #bd944d;"
                                        data-bs-toggle="collapse" data-bs-target="#messageInput">+ Add a message</a>

                                    <div id="messageInput" class="collapse mt-2">
                                        <input type="text" class="form-control"
                                            placeholder="Enter your message here..." name="message">
                                    </div>
                                </div>

                                <hr class="mx-n4">

                                <div class="d-flex align-items-center">
                                    <div class="form-check col-8">
                                        <input class="form-check-input custom-checkbox"
                                            style="transform: scale(0.55);" type="checkbox" name="anonymous"
                                            id="anonymousCheck" value="1">
                                        <label class="form-check-label ms-2" style="font-size: 10px"
                                            for="anonymousCheck">Stay
                                            Anonymous</label>
                                    </div>
                                    <button type="button" class="continue-btn col-4 ms-auto"
                                        onclick="showFinalDetails()">Continue</button>
                                </div>
                            </div>

                            <!-- STEP 2 -->
                            <div class="donation-form final-details" id="step2" style="display: none;">

                                <h5 style="height: 20px; font-size: 15px;"><i class="bi bi-arrow-left"
                                        onclick="goBackToStep1()"></i> <strong>Final Details</strong></h5>

                                <hr class="mx-n4">

                                <div class="d-flex justify-content-between mb-2" style="font-size: 11px">
                                    <p class="mb-0"><strong>Donation:</strong></p>
                                    <strong id="finalAmount">$0</strong>
                                </div>

                                <p style="font-size: 11px"><strong>Credit card processing fees:</strong></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Payment Method Select -->
                                    <select class="form-select me-3 flex-grow-1" style="font-size: 11px"
                                        name="payment_method" required onchange="updateTotals()">
                                        <option value="" selected>Select Payment Method</option>
                                        <option value="visa">Visa & Others</option>
                                        <option value="amex">AMEX Card</option>
                                        <option value="us_bank">US Bank Account</option>
                                        <option value="cash_app">Cash App Pay</option>
                                    </select>
                                    <strong style="font-size: 11px" id="processingFeeDisplay">$0.00</strong>
                                </div>

                                <div class="text-muted mt-3" style="font-size: 8px;">
                                    You pay the CC fee so 100% of your donation goes <br> to your chosen missionary or
                                    cause.
                                </div>


                                <div class="tip-section mt-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong style="font-size: 12px">Add a tip to support Night Bright</strong>
                                            <p class="text-muted mb-0" style="font-size: 8px;">
                                                Why Tip? Night Bright does not charge any platform fees <br>
                                                and relies on your generosity to support this free service.
                                            </p>
                                        </div>
                                        <!-- Tip Percent Select -->
                                        <select class="form-select w-auto" id="tipSelect" name="tip_percent"
                                            style="min-width: 80px;" onchange="updateTotals()">
                                            <option value="0" selected>0%</option>
                                            <option value="5">5%</option>
                                            <option value="10">10%</option>
                                            <option value="15">15%</option>
                                            <option value="20">20%</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-check d-flex align-items-center small">
                                    <input class="form-check-input custom-checkbox  me-2" type="checkbox"
                                        id="contactme" name="contact" style="transform: scale(0.55);">
                                    <label class="form-check-label mb-0" for="contactme" style="font-size: 10px">
                                        Allow Night Bright Inc. to contact me
                                    </label>
                                </div>

                                <hr class="mx-n4">

                                <button type="button" class="finish-btn" id="finishBtn"
                                    onclick="submitToStripe(event)">Finish
                                </button>
                                <style>
                                    .finish-btn {
                                        float: right;
                                        margin-bottom: 3%
                                    }
                                </style>
                            </div>
                        </form>
                    </div>
                </div>
    </section>




    <!-- Loading Overlay -->
    <div id="loadingOverlay"
        style="
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: none;
    z-index: 9999;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
">

        Processing donation, please wait...
    </div>



    <style>
        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        // Handles regular page load
        window.onload = function() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) loadingOverlay.style.display = 'none';
        };

        // Handles page load from cache (e.g., browser "Back" button)
        window.addEventListener('pageshow', function() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                setTimeout(() => {
                    loadingOverlay.style.display = 'none';
                }, 300); // or 3000 for 3s delay
            }
        });
    </script>



<script src="/assets/js/js.js"></script>

</html>
