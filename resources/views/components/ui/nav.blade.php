@php
    $navMenu = [
        ['title' => 'All Products', 'url' => '/'],
        ['title' => '🔥 Populer', 'url' => 'populer'],
        ['title' => '🆕 Recent', 'url' => 'recent'],
    ];
@endphp

<nav class="bg-primary-600" x-data="{ navOpen: false }">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
        <div class="relative flex h-16 items-center justify-between">
            <div class="flex flex-1 items-stretch justify-start">
                <div class="flex flex-shrink-0 items-center">
                    <a href="/" class="text-white font-semibold text-xl mt-1">{{ config('app.name') }}</a>
                </div>
                <div class="ml-6 sm:block hidden">
                    <form action="/search">
                        <div class="flex space-x-4">
                            <x-ui.input />
                        </div>
                    </form>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                @if (!auth()->check())
                    <a href="/admin"
                        class="bg-white px-6 py-2 rounded font-semibold text-primary-600 hover:bg-primary-200">Login</a>
                @else
                    <livewire:user.cart />

                    <!-- Profile dropdown -->
                    <div class="relative ml-3" x-data="{ open: false }">
                        <div>
                            <button type="button" x-on:click="open = !open"
                                class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="absolute -inset-1.5"></span>
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full"
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                    alt="">
                            </button>
                        </div>

                        <!--
                    Dropdown menu, show/hide based on menu state.
        
                    Entering: "transition ease-out duration-100"
                        From: "transform opacity-0 scale-95"
                        To: "transform opacity-100 scale-100"
                    Leaving: "transition ease-in duration-75"
                        From: "transform opacity-100 scale-100"
                        To: "transform opacity-0 scale-95"
                    -->
                        <div x-show="open"
                            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                            tabindex="-1">
                            <a href="{{ filament()->getProfileUrl() }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="user-menu-item-0">Your Profile</a>
                            <a href="/user/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem" tabindex="-1" id="user-menu-item-1">Orders</a>
                            <form action="{{ filament()->getLogoutUrl() }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="block px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 w-full">
                                    Sign out
                                </button>
                            </form>
                            {{-- <a href="" class="" role="menuitem" tabindex="-1" id="user-menu-item-2"></a> --}}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="sm:hidden" id="mobile-menu" x-show="navOpen">
        <div class="space-y-1 px-2 pb-3 pt-2">
            @foreach ($navMenu as $nav)
                <a href="{{ $nav['url'] }}"
                    class="block rounded-md  px-3 py-2 text-base font-medium  {{ Request::is($nav['url']) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                    aria-current="page">{{ $nav['title'] }}</a>
            @endforeach
        </div>
    </div>
</nav>
