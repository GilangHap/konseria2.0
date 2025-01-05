<x-layout>
    <x-slot:title>Konseria - Payment</x-slot>

    <div class="container my-5">
        <div class="row">
            <h1 class="text-primary fw-bold text-center my-2">Ticket Cart</h1>
        </div>
        <div class="row">
            <div class="card col-lg-7 rounded-2 shadow mb-4 p-4"> 
                <h3 class="text-center my-2">Personal Data</h3>
                <form action="{{ route('payment.generateSnapToken') }}" method="post" id="payment-form">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required>
                    </div>     
                    <h3 class="text-center my-2">Ticket Information</h3>
                    <div class="row">
                        <div class="col-8">
                            <h4 class="fw-semibold">{{ $event->title }}</h4>
                            <div class="d-flex">
                                <p class="me-2">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                                <p>x <span id="quantity-display">1</span></p>
                            </div>
                        </div>
                        <div class="col-4 d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-outline-secondary btn-sm me-2" id="decrease-quantity">-</button>
                            <input type="number" class="form-control form-control-sm text-center" id="quantity" name="quantity" value="1" min="1" max="5" style="width: 50px;">
                            <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="increase-quantity">+</button>
                        </div>
                    </div>
            </div>     
            <div class="col-lg-5 ">
                <div class="card rounded-2 shadow p-4">
                    <h3 class="text-center my-2">Summary</h3>
                    <div class="d-flex justify-content-between">
                        <p class="fw-semibold">Subtotal</p>
                        <p id="subtotal">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="fw-semibold">Admin Fee</p>
                        <p id="admin-fee">Rp 5.000</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p class="fw-semibold">Total</p>
                        <p class="fw-semibold" id="total">Rp {{ number_format($event->price + 5000, 0, ',', '.') }}</p>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mt-3" id="pay-button">Proceed to Payment</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(event){
            event.preventDefault();
            const paymentForm = document.getElementById('payment-form');
            
            // Calculate total price including admin fee
            const quantity = parseInt(document.getElementById('quantity').value);
            const ticketPrice = {{ $event->price }};
            const adminFee = 5000;
            const totalPrice = (ticketPrice + adminFee) * quantity;

            // Append total price to the form
            const totalPriceInput = document.createElement('input');
            totalPriceInput.type = 'hidden';
            totalPriceInput.name = 'total_price';
            totalPriceInput.value = totalPrice;
            paymentForm.appendChild(totalPriceInput);

            // Show loading alert
            Swal.fire({
                title: 'Processing Payment',
                text: 'Please wait...',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form to generate SnapToken
            fetch('{{ route('payment.generateSnapToken') }}', {
                method: 'POST',
                body: new FormData(paymentForm),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.snapToken) {
                    // SnapToken acquired from the server
                    snap.pay(data.snapToken, {
                        // Optional
                        onSuccess: function(result){
                            // Update transaction status in the database
                            fetch('{{ url('transaction/update-status') }}/' + data.transaction_uuid, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ status: 'success' })
                            })
                            .then(() => {
                                Swal.fire({
                                    title: 'Payment Successful',
                                    text: 'Your payment has been successfully processed. Transaction details have been sent to your email.',
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                }).then(() => window.location.href = '{{ url('transaction') }}/' + data.transaction_uuid);
                            });
                        },
                        onPending: function(result){
                            Swal.fire({
                                title: 'Payment Pending',
                                text: 'Please complete your payment.',
                                icon: 'info',
                                confirmButtonText: 'OK',
                            });
                        },
                        onError: function(result){
                            Swal.fire({
                                title: 'Payment Failed',
                                text: 'An error occurred. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                            });
                        },
                        onClose: function(){
                            // Handle the close event
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to generate SnapToken. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            });
        };
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quantityInput = document.getElementById('quantity');
            const quantityDisplay = document.getElementById('quantity-display');
            const decreaseQuantityButton = document.getElementById('decrease-quantity');
            const increaseQuantityButton = document.getElementById('increase-quantity');
            const subtotalElement = document.getElementById('subtotal');
            const adminFeeElement = document.getElementById('admin-fee');
            const totalElement = document.getElementById('total');
            const ticketPrice = {{ $event->price }};
            const adminFee = 5000;

            function updateSummary() {
                const quantity = parseInt(quantityInput.value);
                const subtotal = ticketPrice * quantity;
                const totalAdminFee = adminFee * quantity;
                const total = subtotal + totalAdminFee;

                quantityDisplay.textContent = quantity;
                subtotalElement.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                adminFeeElement.textContent = 'Rp ' + totalAdminFee.toLocaleString('id-ID');
                totalElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            decreaseQuantityButton.addEventListener('click', function () {
                if (quantityInput.value > 1) {
                    quantityInput.value--;
                    updateSummary();
                }
            });

            increaseQuantityButton.addEventListener('click', function () {
                if (quantityInput.value < 5) {
                    quantityInput.value++;
                    updateSummary();
                }
            });

            quantityInput.addEventListener('input', function () {
                if (quantityInput.value > 5) {
                    quantityInput.value = 5;
                }
                if (quantityInput.value < 1) {
                    quantityInput.value = 1;
                }
                updateSummary();
            });

            updateSummary();

            // Handle form submission with SweetAlert
            const paymentForm = document.getElementById('payment-form');
            paymentForm.addEventListener('submit', function (event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Processing Payment',
                    text: 'Please wait...',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });
                paymentForm.submit();
            });

            // Display error alert if there is an error message
            @if(session('error'))
                Swal.fire({
                    title: 'Error',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    title: 'Success',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
</x-layout>