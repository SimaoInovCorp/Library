<!doctype html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for interactive UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full">
<div class="min-h-full">
    <nav class="bg-gray-800">
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
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center focus:outline-none">
                                    Library
                                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                    <div class="py-1">
                                        <a href="/books" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Books</a>
                                        <a href="/publishers" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Publishers</a>
                                        <a href="/authors" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Authors</a>
                                        <a href="{{ route('admin.reviews.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Reviews</a>
                                    </div>
                                </div>
                            </div>
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
                        <div class="relative ml-3">
                            <div>
                                <button type="button" class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="absolute -inset-1.5"></span>
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="https://www.svgrepo.com/show/164239/bookshelf.svg" alt="">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    <!-- Mobile menu button -->
                    <button type="button" class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>
                        <!-- Menu open: "hidden", Menu closed: "block" -->
                        <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <!-- Menu open: "block", Menu closed: "hidden" -->
                        <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="md:hidden" id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <a href="/" class="bg-gray-900 text-white block rounded-md px-3 py-2 text-base font-medium" aria-current="page">Home</a>
                <a href="/about" class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">About</a>
                <a href="/contact" class="text-gray-300 hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Contact</a>
            </div>
            <div class="border-t border-gray-700 pb-3 pt-4">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="https://www.svgrepo.com/show/164239/bookshelf.svg" alt="">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-none text-white">Simao Morais</div>
                        <div class="text-sm font-medium leading-none text-gray-400">simaoMorais@InovCorp.com</div>
                    </div>
                    <button type="button" class="relative ml-auto flex-shrink-0 rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                        <span class="absolute -inset-1.5"></span>
                        </svg>
                    </button>
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