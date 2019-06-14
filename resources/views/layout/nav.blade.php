<!-- Header section -->
<header class="header-section">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 text-center text-lg-left">
                    <!-- logo -->
                    <a href="{{ route('welcome') }}" class="site-logo">
                        <img src="{{asset('img/logo.png')}}" alt="logo">
                    </a>
                </div>
                <div class="col-xl-6 col-lg-5">
                    <form class="header-search-form">
                        <input type="text" placeholder="Search on wholesale ....">
                        <button><i class="flaticon-search"></i></button>
                    </form>
                </div>
                <div class="col-xl-4 col-lg-5">
                    <div class="user-panel">
                        <div class="up-item">
                            <i class="flaticon-profile"></i>
                            <a href="{{ route('login') }}">Sign In</a>  or <a href="{{ route('register') }}">Create Account</a>
                        </div>
                        <cart-nav
                            route_add_original="{{ route('cart.add', ['product_id' => 'product_id']) }}"
                            route_fetch_original="{{ route('cart.index') }}"
                            route_remove_original="{{ route('cart.remove', ['product_id' => 'product_id']) }}"
                        ></cart-nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="main-navbar">
        <div class="container">
            <!-- menu -->
            <ul class="main-menu">
                <li><a href="{{ route('welcome') }}">Home</a></li>
                @foreach($categories as $category)
                    <li><a href="{{ route('category', ['categoryTrans' => $category->slug]) }}">{{ $category->title }}</a></li>
                @endforeach
                {{--
                <li><a href="#">Jewelry
                        <span class="new">New</span>
                    </a></li>
                <li><a href="#">Shoes</a>
                    <ul class="sub-menu">
                        <li><a href="#">Sneakers</a></li>
                    </ul>
                </li>
                --}}
                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>
    </nav>
</header>
<!-- Header section end -->