<x-layout>
  <x-slot:title>Konseria - Cari Konsermu</x-slot>

  <!-- Hero -->
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
          <button
              type="button"
              data-bs-target="#heroCarousel"
              data-bs-slide-to="0"
              class="active"
              aria-current="true"
              aria-label="Slide 1"
          ></button>
          <button
              type="button"
              data-bs-target="#heroCarousel"
              data-bs-slide-to="1"
              aria-label="Slide 2"
          ></button>
          <button
              type="button"
              data-bs-target="#heroCarousel"
              data-bs-slide-to="2"
              aria-label="Slide 3"
          ></button>
      </div>
      <div class="carousel-inner">
          <div class="carousel-item active">
              <img src="img/banner1.jpg" class="d-block w-100" alt="Banner 1" />
          </div>
          <div class="carousel-item">
              <img src="img/banner2.png" class="d-block w-100" alt="Banner 2" />
          </div>
          <div class="carousel-item">
              <img src="img/banner3.png" class="d-block w-100" alt="Banner 3" />
          </div>
      </div>
      <button
          class="carousel-control-prev"
          type="button"
          data-bs-target="#heroCarousel"
          data-bs-slide="prev"
      >
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
      </button>
      <button
          class="carousel-control-next"
          type="button"
          data-bs-target="#heroCarousel"
          data-bs-slide="next"
      >
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
      </button>
  </div>
  <!-- End Hero -->

  <!-- Card -->
  <h3 class="text-center fw-bold my-3">Recomendation Events</h3>
  <div class="container">
      <div class="row">
          @foreach($events as $event)
          <div class="col-lg-4 mb-4">
              <div class="card h-100 shadow">
                  <div class="card-img-wrapper">
                      <img
                          src="{{ $event->image ? asset('storage/' . $event->image) : 'default-image-url.png' }}"
                          class="card-img-top img-fluid"
                          alt="{{ $event->title }}"
                      />
                  </div>
                  <div class="card-body">
                        <h3 class="card-title">{{ strlen($event->title) > 28 ? substr($event->title, 0, 28) . '...' : $event->title }}</h3>
                        <p class="card-text"><i class="bi bi-calendar2-week"></i> {{ $event->date }}</p>
                        <p class="card-text"><i class="bi bi-geo-alt-fill"></i> {{ $event->location }}</p>
                        <p class="card-text fw-bold text-primary"><i class="bi bi-cash"></i> {{ formatRupiah($event->price) }}</p>
                        {{-- <a href="{{ route('events.show', $event->id) }}"><button class="btn btn-primary">Beli Tiket</button></a> --}}
                        @if($event->ticket_quota > 0)
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary">Purchase Ticket</a>
                        @else
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-secondary">Sold Out</a>
                        @endif
                  </div>
              </div>
          </div>
          @endforeach
      </div>
  </div>
  <!-- End Card -->
</x-layout>
<style>
  .aspect-ratio {
    aspect-ratio: 644 / 259; /* Rasio 644 x 259 */
    object-fit: cover;
    width: 100%;
    height: auto;
}
.card-img-wrapper {
    position: relative;
    width: 100%;
    padding-top: calc(259 / 644 * 100%); /* Rasio 644 x 259 */
    overflow: hidden;
    border-radius: 8px; /* Opsional, membuat sudut melengkung */
}

.card-img-top {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Pastikan gambar tetap proporsional */
}

</style>
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
  crossorigin="anonymous"
></script>
