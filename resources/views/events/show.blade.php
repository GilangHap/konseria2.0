<x-layout>
    <x-slot:title>{{ $event->title }}</x-slot>
    <div class="container my-5 shadow p-5 rounded-3">
        <div class="row text-center">
            <h1 class="mb-4 fw-bold">{{ $event->title }}</h1>
        </div>
        <div class="row">
            <div class="col-md-8">
                <img 
                    src="{{ $event->image ? asset('storage/' . $event->image) : 'default-image-url.png' }}" 
                    class="img-fluid rounded shadow max-w-full max-h-96 float-end" 
                    alt="Image of {{ $event->title }}"
                />            
            </div>
            <div class="col-md-4">
                <p><strong>Location:</strong> {{ $event->location }}</p>
                <p><strong>Date:</strong> {{ $event->date }}</p>
                <p><strong>Time:</strong> {{ $event->time }}</p>
                <p><strong>Author:</strong> {{ $event->author->name }}</p>
                <p class="fw-bold text-primary"><strong>Price:</strong> {{ formatRupiah($event->price) }}</p>
                @if($event->ticket_quota > 0)
                    <a href="{{ route('payment.show', $event->id) }}" class="btn btn-primary my-3">Purchase Ticket</a>
                @else
                    <button class="btn btn-secondary my-3" disabled>Sold Out</button>
                @endif
            </div>
        </div>
        <div class="row">
            <p class="text-muted px-5 my-3">{!! nl2br(e($event->description)) !!}</p>
        </div>
    </div>
</x-layout>
