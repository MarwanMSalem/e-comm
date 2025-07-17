<div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 220px; min-height: 100vh;">
    <a href="{{ url('/home') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <span class="fs-4 fw-bold">E-Comm</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ url('/home') }}" class="nav-link {{ request()->is('home') ? 'active' : 'link-dark' }}">
                <i class="bi bi-house"></i> Home
            </a>
        </li>
        <li>
            <a href="{{ url('products') }}" class="nav-link {{ request()->is('products*') ? 'active' : 'link-dark' }}">
                <i class="bi bi-box"></i> Products
            </a>
        </li>
        <li>
            <a href="{{ url('orders') }}" class="nav-link {{ request()->is('orders*') ? 'active' : 'link-dark' }}">
                <i class="bi bi-bag"></i> Orders
            </a>
        </li>
        @if(auth()->check() && auth()->user()->role === 'admin')
        <li>
            <a href="{{ url('users') }}" class="nav-link {{ request()->is('users*') ? 'active' : 'link-dark' }}">
                <i class="bi bi-people"></i> Users
            </a>
        </li>
        @endif
    </ul>
    <hr>
    @auth
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>{{ auth()->user()->name }}</strong>
        </a>
        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser">
            <li>
                <form method="POST" action="{{ route('web.logout') }}">
                    @csrf
                    <button class="dropdown-item" type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </div>
    @endauth
</div>
