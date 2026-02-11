<!doctype html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>InovCorp Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for interactive UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full">
<div class="min-h-full">
    <nav class="bg-gray-800" x-data="{ mobileMenuOpen: false }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8" src="https://www.svgrepo.com/show/164239/bookshelf.svg" alt="Your Company">
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <x-nav.nav-link href="/" :active="request()->is('/')">Home</x-nav.nav-link>

                            @if(auth()->check() && auth()->user()->is_admin)
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center focus:outline-none">
                                        Library
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link href="/books">Books</x-dropdown-link>
                                    <x-dropdown-link href="/publishers">Publishers</x-dropdown-link>
                                    <x-dropdown-link href="/authors">Authors</x-dropdown-link>
                                    <x-dropdown-link href="{{ route('admin.reviews.index') }}">Reviews</x-dropdown-link>
                                    <x-dropdown-link href="{{ route('admin.orders.index') }}">Orders</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                            @endif
                            <x-nav.nav-link href="/about" :active="request()->is('about')">About</x-nav.nav-link>
                            <x-nav.nav-link href="/contact" :active="request()->is('contact')">Contact</x-nav.nav-link>
                            @guest
                                <x-nav.nav-link href="{{ route('login') }}" :active="request()->is('login')">Login</x-nav.nav-link>
                                <x-nav.nav-link href="{{ route('register') }}" :active="request()->is('register')">Register</x-nav.nav-link>
                            @endguest
                            @auth
                                <x-nav.nav-link href="{{ route('dashboard') }}" :active="request()->is('dashboard')">Dashboard</x-nav.nav-link>
                                <x-nav.nav-link href="{{ route('requisitions.index') }}" :active="request()->routeIs('requisitions.index')">
                                    My Requests
                                </x-nav.nav-link>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- User info for desktop -->
                        @auth
                        <div class="mr-4">
                            <div class="text-sm font-medium text-white">{{ Auth::user()->name }}</div>
                            <div class="text-xs font-medium text-gray-400">{{ Auth::user()->email }}</div>
                        </div>
                        @endauth

                        <!-- Cart Button (Desktop) -->
                        @auth
                            @if(!Auth::user()->is_admin)
                                <div class="mr-4">
                                    <x-cart.button :count="Auth::user()->cart ? Auth::user()->cart->total_items : 0" />
                                </div>
                            @endif
                        @endauth

                        <!-- Notification Bell -->
                        @auth
                        <div x-data="{ open: false }" class="relative mr-4">
                            <button @click="open = !open" class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                <span class="absolute -inset-1.5"></span>
                                <span class="sr-only">View notifications</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                @php $unread = Auth::user()->unreadNotifications->count(); @endphp
                                @if($unread > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $unread }}</span>
                                @endif
                            </button>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                <div class="py-2 px-4">
                                    <div class="font-semibold text-gray-700 mb-2">Notifications</div>
                                    @if($unread > 0)
                                        <ul class="divide-y divide-gray-200 max-h-60 overflow-y-auto">
                                            @foreach(Auth::user()->unreadNotifications->take(10) as $notification)
                                                <li class="py-2 text-sm text-gray-800 flex justify-between items-center">
                                                    <span>{{ $notification->data['message'] ?? $notification->data['body'] ?? 'Notification' }}</span>
                                                    <form method="POST" action="{{ route('notifications.markAsRead', $notification) }}">
                                                        @csrf
                                                        <button class="text-blue-600 hover:underline text-xs">Mark as read</button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="mt-2 text-right">
                                            <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:underline text-xs">View all</a>
                                        </div>
                                    @else
                                        <div class="text-gray-500 text-sm">No new notifications.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endauth

                        <!-- Profile dropdown -->
                        @auth
                            <x-profile.dropdown />
                        @endauth
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    <!-- Mobile menu button -->
                    <button type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        :aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                        class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                        aria-controls="mobile-menu">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden" id="mobile-menu" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display: none;">
            <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <a href="/" class="bg-gray-900 text-white block rounded-md px-3 py-2 text-base font-medium" aria-current="page">Home</a>
                <a href="/about" class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">About</a>
                <a href="/contact" class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Contact</a>
                @auth
                    @if(!Auth::user()->is_admin)
                        <a href="{{ route('cart.index') }}" class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">
                            <span class="inline-flex items-center">
                                <svg class="w-5 h-5 mr-1 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 007.5 19h9a2 2 0 001.85-1.3L17 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7" />
                                </svg>
                                Cart
                                @if(Auth::user()->cart && Auth::user()->cart->total_items > 0)
                                    <span class="ml-2 bg-red-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5 min-w-[1.5em] text-center">{{ Auth::user()->cart->total_items }}</span>
                                @endif
                            </span>
                        </a>
                    @endif
                @endauth
            </div>
            <div class="border-t border-gray-700 pb-3 pt-4">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="https://www.svgrepo.com/show/164239/bookshelf.svg" alt="">
                    </div>
                    @auth
                        <div class="ml-3">
                            <div class="text-base font-medium leading-none text-white">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium leading-none text-gray-400">{{ Auth::user()->email }}</div>
                        </div>
                    @endauth
                    @auth
                        <div class="md:hidden ml-auto" style="z-index:60;">
                            <x-dropdown align="right" width="48" dropdownClasses="mt-2">
                                <x-slot name="trigger">
                                    <button class="flex items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                        <span class="sr-only">Open user menu</span>
                                        <img class="h-8 w-8 rounded-full" src="https://www.svgrepo.com/show/164239/bookshelf.svg" alt="">
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link href="{{ route('profile.show') }}">Profile</x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                            Logout
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @if(isset($header))
        <x-page.header>
            {{ $header }}
        </x-page.header>
    @elseif(isset($heading))
        <x-page.header>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $heading }}</h1>
        </x-page.header>
    @endif

    <main>
        <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>
</div>
</body>
</html>