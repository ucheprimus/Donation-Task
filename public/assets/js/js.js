
        const overlay = document.getElementById('donateOverlay');
        const donateBtn = document.querySelector('.donate-btn');
        const hamburger = document.querySelector('.hamburger');
        const nav = document.querySelector('nav');

        function openOverlay() {
            overlay.classList.add('open');
        }

        function closeOverlay() {
            overlay.classList.remove('open');
        }

        // Toggle overlay on donate button click
        donateBtn.addEventListener('click', () => {
            if (overlay.classList.contains('open')) {
                closeOverlay();
            } else {
                openOverlay();
            }
        });

        // Close overlay on outside click
        document.addEventListener('click', (event) => {
            if (overlay.classList.contains('open') &&
                !overlay.contains(event.target) &&
                !donateBtn.contains(event.target)) {
                closeOverlay();
            }
        });

        // Toggle hamburger menu
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            nav.classList.toggle('open');
        });

        // Close navbar when clicking a nav link
        nav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                nav.classList.remove('open');
            });
        });


        let selectedAmount = "25$";
        let donationType = "One-Time";

        // Function to select a donation type (One-Time or Monthly)
        function selectTab(btn, type) {
            document.querySelectorAll('.tab-selector button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            donationType = type;
            console.log("Selected type:", donationType);
        }

        // Function to select a donation amount

        function selectAmount(btn) {
            document.querySelectorAll('#amountButtons button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedAmount = btn.innerText.replace('$', '');
            handleOtherAmountSelection(btn);
            console.log("Selected amount:", selectedAmount);
        }

        function handleOtherAmountSelection(btn) {
            const isOther = btn.innerText.toLowerCase().includes('other');
            const customAmountInput = document.getElementById('selectedAmount');

            if (isOther) {
                customAmountInput.style.display = 'block';
                selectedAmount = ''; // wait for user input
            } else {
                customAmountInput.style.display = 'none';
                customAmountInput.value = '';
                selectedAmount = btn.innerText.replace('$', '');
            }
        }

        // Function to show the final details step
        function showFinalDetails() {
            const customAmount = document.getElementById('selectedAmount').value;
            if (customAmount) selectedAmount = customAmount;

            if (!selectedAmount || isNaN(selectedAmount) || selectedAmount <= 0) {
                showError('Please enter a valid donation amount.');
                return;
            }

            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
            updateTotals();
        }

        function updateTotals() {
            const amount = parseFloat(document.getElementById('selectedAmount').value || selectedAmount || 0);
            const paymentMethod = document.querySelector('select[name="payment_method"]').value;
            const tipPercent = parseFloat(document.getElementById('tipSelect')?.value ||
                0); // Default to 0% as per your requirement

            // Processing fee based on payment method
            let fee;
            switch (paymentMethod) {
                case 'visa':
                    fee = 0.85;
                    break;
                case 'amex':
                    fee = 0.88;
                    break;
                case 'us_bank':
                    fee = 0.20;
                    break;
                case 'cash_app':
                    fee = 1.03;
                    break;
                default:
                    fee = 0.00; // Default when no payment method is selected
            }

            // Calculate tip
            const tip = ((amount * tipPercent) / 100).toFixed(2);
            // Calculate total
            const total = (amount + fee + parseFloat(tip)).toFixed(2);

            // Update DOM elements
            document.getElementById('finalAmount').innerText = `$${amount.toFixed(2)}`;
            document.getElementById('processingFeeDisplay').innerText = `$${fee.toFixed(2)}`;
            document.getElementById('finishBtn').innerText = `Finish ($${total})`;
        }

        // Function to go back to step 1 from final details
        function goBackToStep1() {
            // Hide step2 and show step1
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step1').style.display = 'block';
        }


        function addMessage() {
            // Create a new input element
            var inputField = document.createElement('input');
            inputField.type = 'text';
            inputField.placeholder = 'Enter your message here...';
            inputField.style.width = '100%'; // Adjust the width as needed
            inputField.style.padding = '5px'; // Optional styling for the input field

            // Replace the link with the input field
            var optionsDiv = document.querySelector('.options');
            optionsDiv.innerHTML = ''; // Clear the options div
            optionsDiv.appendChild(inputField); // Add the input field
        }



        function submitToStripe(event) {
            if (event) event.preventDefault();

            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'flex'; // Show loading

            const form = document.getElementById('donationForm');
            const formData = new FormData(form);

            const selectedAmountInput = document.getElementById('selectedAmount');
            let baseAmount = selectedAmountInput.value.trim();

            if (!baseAmount) {
                const activeBtn = document.querySelector('#amountButtons .active');
                if (activeBtn && !activeBtn.textContent.toLowerCase().includes('other')) {
                    baseAmount = activeBtn.textContent.replace('$', '').trim();
                }
            }

            if (!baseAmount || isNaN(baseAmount) || parseFloat(baseAmount) <= 0) {
                showError('Failed to process donation. Please try again.');
                loadingOverlay.style.display = 'none'; // Hide loading on error
                return;
            }

            baseAmount = parseFloat(baseAmount);

            const fee = parseFloat((baseAmount * 0.029 + 0.30).toFixed(2));
            const tipPercent = parseFloat(document.getElementById('tipSelect')?.value || 0);
            const tip = parseFloat(((baseAmount * tipPercent) / 100).toFixed(2));
            const totalAmount = parseFloat((baseAmount + fee + tip).toFixed(2));

            formData.set('selected_amount', totalAmount);
            formData.set('anonymous', form.querySelector('#anonymousCheck').checked ? '1' : '0');
            formData.set('contact', form.querySelector('#contactme').checked ? '1' : '0');

            const donorName = formData.get('donor_name').trim();
            const email = formData.get('email').trim();
            if (!donorName || !email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('Please provide a valid donor name and email.');
                
                loadingOverlay.style.display = 'none'; // Hide loading on error
                return;
            }

            fetch('/donate', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.payment_url) {
                        window.location.href = data.payment_url; // redirect = no need to hide overlay
                    } else {
                        showError('Error: ' + (data.error || 'Unknown error'));
                        loadingOverlay.style.display = 'none'; // Hide on error
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    showError('Failed to process donation. Please try again.');
                    loadingOverlay.style.display = 'none'; // Hide on failure
                });
        }


        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.innerText = message;
            errorDiv.style.display = 'block';
        }
        