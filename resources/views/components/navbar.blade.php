    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-primary navbar-dark">
        <div class="container">
          <a class="navbar-brand" href="/">
            <img
              src="{{ asset('img/logo_kuning.png') }}"
              alt="Logo"
              width="30"
              height="30"
              class="d-inline-block align-text-top"
            />
            Konseria
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0">
              <li class="nav-item me-3">
                <a class="{{ request()->is('/')? 'nav-link active' : 'nav-link'}}" aria-current="page" href="/">Events</a>
              </li>
              <li class="nav-item me-3">
                <a class="{{ request()->is('about')? 'nav-link active' : 'nav-link'}}" href="/about">About Us</a>
              </li>
            </ul>
            <a class="btn btn-warning" href="{{ url('/admin') }}">Create Event</a>          
          </div>
        </div>
      </nav>
      <!-- End Navbar -->