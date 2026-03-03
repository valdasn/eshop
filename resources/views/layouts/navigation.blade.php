<nav class="shop-nav-dark">
    <div class="container nav-container">
        <div class="nav-left">
            <a href="{{ route('home') }}" class="logo">VitaShop</a>
            <div class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') && !request()->has('all') ? 'active' : '' }}">Home</a>
                <a href="{{ route('home', ['all' => 'true']) }}" class="{{ request()->has('all') ? 'active' : '' }}">Shop All</a>
            </div>
        </div>
        <div class="nav-right">
            @auth
                <div class="dropdown">
                    <button class="dropbtn">
                        {{ Auth::user()->name }} ▼
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('profile.edit') }}">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            @else
                <div class="auth-links">
                    <a href="{{ route('login') }}">Log in</a>
                    <a href="{{ route('register') }}" class="btn-small">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>