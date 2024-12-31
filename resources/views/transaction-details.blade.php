<x-layout>
    <x-slot:title>Transaction Details</x-slot>

    <div class="container md-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="text-primary fw-bold text-center my-2">Transaction Details</h1>
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif              
                <div class="card p-4 mb-4 shadow-m rounded-2">
                    <h3 class="mb-1 fw-bold">Transaction Information</h3>
                    <hr class="border border-primary border-3 opacity-75">
                    <p><strong>Name:</strong> {{ $transaction->name }}</p>
                    <p><strong>Email:</strong> {{ $transaction->email }}</p>
                    <p><strong>NIK:</strong> {{ $transaction->nik }}</p>
                    <p><strong>Total Price:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                </div>
                <div class="card p-4 shadow-sm rounded">
                    <h3 class="mb-3 fw-bold">Tickets</h3>
                    @foreach($transaction->tickets as $ticket)
                        <div class="ticket mb-3 p-3 d-flex flex-column flex-lg-row align-items-center shadow rounded-2">
                            <div class="ticket-info me-3">
                                <p><strong>Ticket Code:</strong> {{ $ticket->ticket_code }}</p>
                                <p><strong>Event:</strong> {{ $transaction->event->title }}</p>
                                <p><strong>Date:</strong> {{ $transaction->event->date }}</p>
                                <p><strong>Time:</strong> {{ $transaction->event->time }}</p>
                                <p><strong>Location:</strong> {{ $transaction->event->location }}</p>
                            </div>
                            <div class="tear-line d-none d-lg-block"></div>
                            <div class="qr-code mx-auto mt-3 mt-lg-0">
                                {!! QrCode::size(100)->generate($ticket->ticket_code) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="btn btn-primary my-4">Download Ticket</button>
            </div>
        </div>
    </div>

    <style>
        body {
            background-color: #f9f9f9;
        }
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .ticket {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            padding: 15px;
            position: relative;
        }
        .ticket-info {
            flex: 1;
        }
        .tear-line {
            width: 2px;
            height: 100%;
            background: repeating-linear-gradient(
                to bottom,
                white,
                white 8px,
                black 8px,
                black 12px
            );
            position: absolute;
            left: 80%;
        }
        .qr-code {
            margin-left: auto;
            margin-right: auto;
        }
        .btn-primary {
            background-color: #6c63ff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #5848c2;
        }
        .custom-hr {
            border: 2px solid blue;
            border-radius: 5px;
        }
    </style>
</x-layout>