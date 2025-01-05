<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 650px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .header {
            background: linear-gradient(135deg, #003b7c, #00509e);
            color: #ffffff;
            text-align: center;
            padding: 20px 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px 30px;
        }
        .content p {
            margin: 10px 0;
            color: #444444;
            line-height: 1.5;
        }
        .content h3 {
            margin-bottom: 15px;
            color: #003b7c;
            font-size: 18px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 5px;
        }
        .content strong {
            color: #222222;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .details-table td {
            padding: 10px 5px;
            font-size: 14px;
            border-bottom: 1px solid #eeeeee;
        }
        .details-table td:first-child {
            font-weight: bold;
            color: #333333;
        }
        .ticket {
            background: #f9f9f9;
            border: 1px solid #eeeeee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .ticket p {
            margin: 8px 0;
            font-size: 14px;
            color: #555555;
        }
        .qr-code {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #eeeeee;
            display: inline-block;
        }
        .footer {
            background: #003b7c;
            color: #ffffff;
            text-align: center;
            padding: 15px 30px;
            font-size: 13px;
        }
        .footer span {
            font-weight: bold;
            color: #ffd700;
        }
        .cta {
            margin-top: 20px;
            text-align: center;
        }
        .cta a {
            background: #00509e;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
        }
        .cta a:hover {
            background: #003b7c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Transaction Receipt</h1>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $transaction->name }}</strong>,</p>
            <p>Your transaction has been successfully processed. Below are the details of your transaction:</p>

            <h3>Transaction Details</h3>
            <table class="details-table">
                <tr>
                    <td>Name</td>
                    <td>{{ $transaction->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $transaction->email }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>{{ $transaction->nik }}</td>
                </tr>
                <tr>
                    <td>Total Price</td>
                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>

            <h3>Tickets</h3>
            @foreach($transaction->tickets as $ticket)
                <div class="ticket">
                    <p><strong>Ticket Code:</strong> {{ $ticket->ticket_code }}</p>
                    <p><strong>Event:</strong> {{ $transaction->event->title }}</p>
                    <p><strong>Date:</strong> {{ $transaction->event->date }}</p>
                    <p><strong>Time:</strong> {{ $transaction->event->time }}</p>
                    <p><strong>Location:</strong> {{ $transaction->event->location }}</p>
                    <div class="qr-code">
                        {!! QrCode::size(100)->generate($ticket->ticket_code) !!}
                    </div>
                </div>
            @endforeach

            <div class="cta">
                <a href="{{ route('transaction.download', $transaction->uuid) }}">Download Your Tickets</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2024 <span>Konseria</span>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
