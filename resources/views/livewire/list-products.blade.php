@php
    $navMenu = [
        ['title' => 'All', 'slug' => 'all', 'checked' => true],
        ['title' => '🔥 Populer', 'slug' => 'populer', 'checked' => false],
        ['title' => '🆕 Recent', 'slug' => 'recent', 'checked' => false],
    ];
@endphp

<div>
    <h2 class="text-2xl font-bold mb-4">{{ $title }}</h2>

    <ul class="flex gap-2 mb-8">
        @foreach ($navMenu as $menu)
            <li>
                <input type="radio" class="peer hidden" name="nav" id="nav-{{ $menu['slug'] }}"
                    value="{{ $menu['slug'] }}" {{ $menu['checked'] ? 'checked' : '' }}>
                <label for="nav-{{ $menu['slug'] }}"
                    class="peer-checked:bg-primary-300 peer-checked:ring-primary-500 font-semibold peer-checked:text-primary-600 text-gray-600 ring-1 rounded-full px-4 py-1 bg-gray-50 ring-gray-500 hover:bg-gray-200">{{ $menu['title'] }}</label>
            </li>
        @endforeach
    </ul>
    <div class="grid grid-cols-2 gap-x-6 gap-y-10 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
        @forelse ($products as $product)
            <x-product-card :$product />
        @empty
            <div class="col-span-full w-full">
                <p class="bg-primary-400 text-white rounded p-2">Produt tidak
                    ditemukan
                </p>
            </div>
        @endforelse
    </div>


</div>
