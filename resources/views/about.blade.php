<x-layout>
    <x-slot:title>Konseria - About Us</x-slot>
    <div class="container my-5">
        <div class="row">
            <div class="col text-center">
                <h1 class="display-4">About Us</h1>
                <p class="lead">Learn more about Konseria and our mission.</p>
            </div>
        </div>
        <div class="row my-5">
            <div class="col-md-6">
                <img src="{{ asset('img/about-us.jpg') }}" class="img-fluid rounded shadow" alt="About Us">
            </div>
            <div class="col-md-6">
                <h2>Our Mission</h2>
                <p>At Konseria, our mission is to bring the best concert experiences to music lovers everywhere. We believe in the power of live music to connect people and create unforgettable memories.</p>
                <h2>Our Story</h2>
                <p>Founded in 2024, Konseria started as a small project by a group of friends who shared a passion for music. Today, we are proud to be one of the leading platforms for concert discovery and ticketing.</p>
            </div>
        </div>
        <div class="row my-5">
            <div class="col text-center">
                <h2>Meet the Team</h2>
            </div>
        </div>
        <div class="row text-center justify-content-center">
            <div class="col-md-3">
                <img src="{{ asset('img/gilang.jpg') }}" class="img-fluid rounded-circle mb-3" style="max-width: 200px; max-height: 200px;" alt="Team Member 1">
                <h5>John Doe</h5>
                <p>CEO & Founder</p>
            </div>
            <div class="col-md-3">
                <img src="{{ asset('img/gilang.jpg') }}" class="img-fluid rounded-circle mb-3" style="max-width: 200px; max-height: 200px;" alt="Team Member 2">
                <h5>Jane Smith</h5>
                <p>Chief Marketing Officer</p>
            </div>
            <div class="col-md-3">
                <img src="{{ asset('img/gilang.jpg') }}" class="img-fluid rounded-circle mb-3" style="max-width: 200px; max-height: 200px;" alt="Team Member 3">                <h5>Mike Johnson</h5>
                <p>Head of Operations</p>
            </div>
        </div>
    </div>
</x-layout>