<x-layout>
    <x-slot:title>Konseria - Payment</x-slot>

    <div class="container">
        <div class="row">
            <h1 class="text-primary fw-bold text-center my-2">Ticket Cart</h1>
        </div>
        <div class="row">
            <div class="card col-lg-7 rounded-2 shadow mb-4 p-4"> 
                <h3 class="text-center my-2">Personal Data</h3>
                <form action="{{ route('payment.process') }}" method="post" id="payment-form">
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
                    <button type="submit" class="btn btn-primary w-100 mt-3">Proceed to Payment</button>
                </div>
                </form>
            </div>
        </div>
    </div>

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
        });
    </script>
</x-layout>