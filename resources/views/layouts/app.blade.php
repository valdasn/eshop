<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaShop - Your Vitamin Store</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
   <nav class="shop-nav-dark">
    <div class="container nav-container">

        <a href="{{ route('home') }}" class="logo">VitaShop</a>

         <form action="{{ route('products.index') }}" method="GET" style="display: flex; gap: 5px; max-width: 400px; margin: 10px auto;">
        <input type="text" name="search" placeholder="Search vitamins..." value="{{ request('search') }}" 
           style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
        <button type="submit" class="btn" style="padding: 10px 20px; background: var(--primary-green); color: white; border: none; border-radius: 5px; cursor: pointer;">
        Search
        </button>
        </form>
        
        <div class="nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') && !request()->has('all') ? 'active' : '' }}">Home</a>
            <a href="{{ route('home', ['all' => 'true']) }}" class="{{ request()->has('all') ? 'active' : '' }}">Shop All</a>
            
            <div class="dropdown">
                <button class="dropbtn">Categories</button>
                <div class="dropdown-content">
                    @foreach($allCategories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('cart.index') }}" class="cart-link">
             🛒 Cart 
             @php $totalQty = array_sum(array_column(session('cart', []), 'quantity')); @endphp
            <span id="cart-count" class="cart-badge" style="{{ $totalQty > 0 ? '' : 'display: none;' }}">
                {{ $totalQty }}
            </span>
            </a>

            @auth
                <div class="dropdown">
                    <button class="dropbtn">
                        Hi, {{ explode(' ', Auth::user()->name)[0] }} ▾
                    </button>
                    <div class="dropdown-content" style="right: 0; left: auto;">
                        <a href="{{ route('profile.edit') }}">My Profile</a>
                        <a href="{{ route('orders.history') }}">My Orders</a>
                        
                        <hr style="border: 0; border-top: 1px solid #eee; margin: 0;">
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </a>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}" class="btn-small">Register</a>
            @endauth
        </div>
    </div>
</nav>

    <main class="container" style="padding-top: 30px;">
        @if(session('success'))
            <div class="alert-success" style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
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
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json()) // We need to convert the response to JSON
        .then(data => {
            if (data.status === 'success') {
                // 1. Find the cart counter span
                const cartCounter = document.getElementById('cart-count');
                
                if (cartCounter) {
                    // 2. Update the number with the totalQty from the controller
                    cartCounter.innerText = data.cartCount;
                    
                    // 3. Make sure it's visible (in case it was hidden at 0)
                    cartCounter.style.display = 'inline-block';

                    // Optional: Add a little "pop" animation
                    cartCounter.style.transform = 'scale(1.2)';
                    setTimeout(() => cartCounter.style.transform = 'scale(1)', 200);
                }

                // 4. Visual feedback on the button
                const btn = this.querySelector('.btn');
                const originalText = btn.innerText;
                btn.innerText = 'Added! ✓';
                btn.style.background = '#2f855a'; 

                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.style.background = ''; 
                }, 2000);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
</body>
</html>