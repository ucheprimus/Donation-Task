@extends('layouts.app')

@section('title')
    Welcome, Make your contribution
@endsection

@section('content')
<div class="side-donate">
    <button class="donate-btn" onclick="showDonationWidget()">
        Donate <span>→</span>
    </button>
    <p class="donate-text">
        Make donating to your favorite causes an enjoyable experience with thousands of beautiful people spreading the love and making it easier to find, fund, and resource missions
    </p>
</div>

<div class="donation-widget" id="step1" style="display: none;">
    <div class="widget-header">
        <h2>DONATE</h2>
        <button class="close-btn" onclick="closeDonationWidget()">×</button>
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
            <div class="donation-type">
                <h3>Missionary Donation</h3>
                <div class="donation-toggle">
                    <button type="button" class="active" data-type="one-time">One-Time</button>
                    <button type="button" data-type="monthly">Monthly</button>
                </div>
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="donor_name" placeholder="Donor's Name" value="{{ old('donor_name') }}" required>
                <input type="email" class="form-control" name="email" placeholder="Donor's Email" value="{{ old('email') }}" required>
            </div>

            <input type="text" class="form-control" name="night_bright" placeholder="Night Bright" value="{{ old('night_bright') }}">

            <div class="amount-grid">
                <button type="button" class="amount-btn" data-amount="10">10$</button>
                <button type="button" class="amount-btn" data-amount="25">25$</button>
                <button type="button" class="amount-btn" data-amount="50">50$</button>
                <button type="button" class="amount-btn" data-amount="100">100$</button>
                <button type="button" class="amount-btn" data-amount="250">250$</button>
                <button type="button" class="amount-btn" data-amount="500">500$</button>
                <button type="button" class="amount-btn" data-amount="1000">1000$</button>
                <button type="button" class="amount-btn" data-amount="other">Other</button>
            </div>

            <div class="custom-amount-input" id="customAmountInputContainer">
                <input type="number" class="form-control" name="amount" id="customAmountInput" placeholder="Enter custom amount" min="1" value="{{ old('amount') }}">
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="anonymous" id="anonymousCheck" value="1" {{ old('anonymous') ? 'checked' : '' }}>
                <label class="form-check-label" for="anonymousCheck">Stay Anonymous</label>
            </div>

            <input type="hidden" name="donation_type" id="donationTypeInput" value="one-time">
            <input type="hidden" name="selected_amount" id="selectedAmountInput">
            <input type="hidden" name="payment_method" id="paymentMethodInput">
            <button type="button" class="continue-btn" onclick="showStep2()">Continue</button>
        </form>
    </div>
</div>

<div class="donation-widget" id="step2" style="display: none;">
    <div class="widget-header">
        <h2>DONATE</h2>
        <button class="close-btn" onclick="closeDonationWidget()">×</button>
    </div>
    
    <div class="widget-content">
        <div class="back-header">
            <button type="button" class="back-btn" onclick="showStep1()">← Final Details</button>
        </div>

        <div id="validationErrors" class="alert alert-danger" style="display: none;">
            <p id="errorMessage"></p>
        </div>

        <div class="donation-summary">
            <div class="d-flex justify-content-between mb-2">
                <span>Donation</span>
                <span id="summaryAmount">$0.00</span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Credit card processing fees</span>
                <span id="summaryFees">$0.00</span>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn-payment dropdown-toggle" type="button" data-bs-toggle="dropdown" id="paymentMethodBtn">
                Visa & Others
                <span class="caret">▼</span>
            </button>
            <ul class="dropdown-menu w-100">
                <li><button class="dropdown-item" type="button" data-method="amex">AMEX Card</button></li>
                <li><button class="dropdown-item" type="button" data-method="visa">Visa & Others</button></li>
                <li><button class="dropdown-item" type="button" data-method="us_bank">US Bank Account</button></li>
                <li><button class="dropdown-item" type="button" data-method="cash_app">Cash App Pay</button></li>
            </ul>
        </div>

        <div class="alert alert-warning">
            Why? Night Bright now charges platform fees that takes on your generosity to support this free service
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="contact" id="contactCheck" {{ old('contact') ? 'checked' : '' }}>
            <label class="form-check-label" for="contactCheck">
                Allow Night Bright to contact me
            </label>
        </div>

        <button type="submit" class="finish-btn" form="donationForm">Finish ($0.00)</button>
    </div>
</div>

<script>
    document.querySelector('.finish-btn').addEventListener('click', function(event) {
        // Get form inputs
        const donorName = document.querySelector('input[name="donor_name"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const selectedAmount = document.querySelector('input[name="selected_amount"]').value;
        const customAmount = document.querySelector('input[name="amount"]').value;
        const errorContainer = document.getElementById('validationErrors');
        const errorMessage = document.getElementById('errorMessage');

        // Reset error container
        errorContainer.style.display = 'none';
        errorMessage.textContent = '';

        // Check if fields are empty
        if (!donorName) {
            errorMessage.textContent = 'Please enter the donor\'s name.';
            errorContainer.style.display = 'block';
            event.preventDefault();
            return;
        }

        if (!email) {
            errorMessage.textContent = 'Please enter the donor\'s email.';
            errorContainer.style.display = 'block';
            event.preventDefault();
            return;
        }

        // Validate email format
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            errorMessage.textContent = 'Please enter a valid email address.';
            errorContainer.style.display = 'block';
            event.preventDefault();
            return;
        }

        // Check if either selected_amount or custom_amount is provided
        if (!selectedAmount && !customAmount) {
            errorMessage.textContent = 'Please select or enter a donation amount.';
            errorContainer.style.display = 'block';
            event.preventDefault();
            return;
        }

        // If all validations pass, the form will submit normally
    });
</script>
@endsection