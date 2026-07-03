<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests; script-src 'self' 'unsafe-inline' 'unsafe-eval';">
    <title>VitaShop - Your Vitamin Store</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    @if(isset($page))
        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    @endif
</head>
<body>
<nav class="shop-nav-dark">
    <div class="container nav-container">
        <a href="{{ route('home') }}" class="logo">VitaShop</a>

       <form action="{{ secure_url(route('products.index', [], false)) }}" method="GET" class="nav-search-form">
        <div class="search-input-wrapper">
        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
        <button type="submit" class="btn-search-icon">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
        </div>
        </form>
        
        <div class="nav-links">
            <div class="dropdown">
                <button class="dropbtn">Categories
                    <svg class="chevron-icon" viewBox="0 0 24 24" width="16" height="16">
                    <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="dropdown-content">
                     <a href="{{ route('products.index', ['all' => 'true']) }}" class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}">Shop All</a>
                    @foreach($allCategories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>

            @auth
                <div class="dropdown">
                    <button class="dropbtn">Hi, {{ explode(' ', Auth::user()->name)[0] }} 
                        <svg class="chevron-icon" viewBox="0 0 24 24" width="16" height="16">
                    <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    </button>
                    <div class="dropdown-content dropdown-right">
                        <a href="{{ route('profile.edit') }}">My Profile</a>
                        <a href="{{ route('orders.history') }}">My Orders</a>
                        <hr class="dropdown-divider">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-link">Logout</button>
                        </form>
                    </div>
                </div>
            @else
            <div class="dropdown">
                <button class="dropbtn">Account
                    <svg class="chevron-icon" viewBox="0 0 24 24" width="16" height="16">
                    <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="dropdown-content dropdown-right">
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}" class="nav-register-link">Register</a>
                </div>
            </div>
            @endauth

            <a href="{{ route('cart.index') }}" class="cart-link">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="nav-icon-svg">
        <circle cx="9" cy="21" r="1"></circle>
        <circle cx="20" cy="21" r="1"></circle>
        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </svg>
    <span class="cart-badge">0</span>
               @php $totalQty = array_sum(array_column(session('cart', []), 'quantity')); @endphp
               <span id="cart-count" class="cart-badge" style="{{ $totalQty > 0 ? '' : 'display: none;' }}">
                    {{ $totalQty }}
               </span>
            </a>

        </div>
    </div>
</nav>

<main class="container main-content">
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(isset($page))
        @inertia
    @else
        @yield('content')
    @endif
</main>

<script>
    document.querySelectorAll('.ajax-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const formData = new FormData(this);
            const action = this.getAttribute('action');

            fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const cartCounter = document.getElementById('cart-count');
                    if (cartCounter) {
                        cartCounter.innerText = data.cartCount;
                        cartCounter.style.display = 'inline-block';
                        cartCounter.style.transform = 'scale(1.2)';
                        setTimeout(() => cartCounter.style.transform = 'scale(1)', 200);
                    }
                    const btn = this.querySelector('.btn');
                    if(btn) {
                        const originalText = btn.innerText;
                        btn.innerText = 'Added! ✓';
                        btn.classList.add('btn-added');
                        setTimeout(() => {
                            btn.innerText = originalText;
                            btn.classList.remove('btn-added');
                        }, 2000);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
</body>
</html>