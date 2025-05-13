// DOM elements
const step1 = document.getElementById('step1');
const step2 = document.getElementById('step2');
const amountButtons = document.querySelectorAll('#step1 .amount-btn');
const customAmountInputContainer = document.getElementById('customAmountInputContainer');
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

// here, I Initialize default values
function initialize() {
    paymentMethodInput.value = 'visa';
    paymentMethodBtn.textContent = 'Visa & Others';
    paymentMethodBtn.appendChild(createCaretSpan());
    if (customAmountInput && customAmountInput.value) {
        selectedAmount = parseFloat(customAmountInput.value) || 0;
        customAmountInputContainer.classList.add('active');
        document.querySelector('.amount-btn[data-amount="other"]')?.classList.add('active');
    }
    updateSummary();
}

//  for dropdown
function createCaretSpan() {
    const span = document.createElement('span');

    return span;
}

// my donation summary updating
function updateSummary() {
    const fees = selectedAmount * 0.029 + 0.30; 
    summaryAmount.textContent = `$${selectedAmount.toFixed(2)}`;
    summaryFees.textContent = `$${fees.toFixed(2)}`;
    selectedAmountInput.value = selectedAmount || 0;
    const total = selectedAmount > 0 ? (selectedAmount + fees).toFixed(2) : '0.00';
    document.querySelector('.finish-btn').textContent = `Finish ($${total})`;
}

// select tamount
amountButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        amountButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const amount = btn.dataset.amount;
        if (amount === 'other') {
            customAmountInputContainer.classList.add('active');
            customAmountInput.focus();
            selectedAmount = parseFloat(customAmountInput.value) || 0;
        } else {
            customAmountInputContainer.classList.remove('active');
            selectedAmount = parseFloat(amount);
            customAmountInput.value = '';
        }
        updateSummary();
    });
});

// Custom amount input for those that selects others
if (customAmountInput) {
    customAmountInput.addEventListener('input', () => {
        selectedAmount = parseFloat(customAmountInput.value) || 0;
        amountButtons.forEach(b => b.classList.remove('active'));
        const otherBtn = document.querySelector('.amount-btn[data-amount="other"]');
        if (otherBtn) otherBtn.classList.add('active');
        customAmountInputContainer.classList.add('active');
        updateSummary();
    });
}

// Toggle donation type
document.querySelectorAll('.donation-toggle button').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelector('.donation-toggle .active')?.classList.remove('active');
        btn.classList.add('active');
        donationTypeInput.value = btn.dataset.type || 'one-time';
        console.log('Donation type selected:', donationTypeInput.value);
    });
});

// Payment method selection
document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', () => {
        paymentMethodBtn.textContent = item.textContent;
        paymentMethodBtn.appendChild(createCaretSpan());
        paymentMethodInput.value = item.dataset.method;
        console.log('Payment method selected:', paymentMethodInput.value);
    });
});

// Show donation widget
function showDonationWidget() {
    step1.style.display = 'block';
    step2.style.display = 'none';
}

// Close donation widget
function closeDonationWidget() {
    step1.style.display = 'none';
    step2.style.display = 'none';
}

// Step navigation
function showStep1() {
    step2.style.display = 'none';
    step1.style.display = 'block';
}

function showStep2() {
    if (selectedAmount <= 0) {
        alert('Please select or enter a donation amount.');
        return;
    }
    step1.style.display = 'none';
    step2.style.display = 'block';
    
}

// Form submission with Stripe redirect
if (donationForm) {
    donationForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        loadingScreen.style.display = 'flex';

        const formData = new FormData(donationForm);
        try {
            const response = await fetch('/donation/process', { // Hardcoded for now, replace with dynamic route
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                },
            });

            const text = await response.text();
            let result;
            try {
                result = JSON.parse(text);
            } catch (e) {
                throw new Error('Invalid JSON response: ' + text.substring(0, 100));
            }

            console.log('Backend response:', result);

            if (response.ok && result.payment_url) {
                window.location.href = result.payment_url;
            } else {
                loadingScreen.style.display = 'none';
                const errorMessage = result.errors ? result.errors.join('\n') : 'An unexpected error occurred. Please try again.';
                alert(errorMessage);
            }
        } catch (error) {
            loadingScreen.style.display = 'none';
            console.error('Submission error:', error);
            alert('An error occurred: ' + error.message);
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initialize);