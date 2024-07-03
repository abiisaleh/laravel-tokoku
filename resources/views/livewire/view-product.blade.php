<div class="grid sm:grid-cols-2 gap-4 mx-auto max-w-2xl py-6 sm:py-8 lg:max-w-7xl">
    <div>
        <img src="/storage/{{ $product->gambar }}" alt="Two each of gray, white, and black shirts laying flat."
            class="h-full w-full aspect-1 object-cover object-center rounded-xl">
    </div>
    <div class="px-2">

        <div class="border-b border-gray-200 mb-4 space-y-8">
            <div class="space-y-2">
                <h1 class="font-bold text-3xl">{{ $product->nama }}</h1>
                <div class="flex justify-between">
                    <div class="flex items-center">
                        <a href="/category/{{ $product->category->id }}">
                            <x-ui.badge>{{ $product->category->nama }}</x-ui-badge>
                        </a>
                    </div>
                    <p class="text-xl lg:text-3xl font-bold text-primary-600">Rp.
                        {{ number_format($product->harga) }}
                    </p>
                </div>
            </div>
            <div class="flex gap-2 items-center pb-8">
                {{ $this->addToCart }}

                {{ $this->buy }}

                <x-filament-actions::modals />
            </div>
        </div>

        <h6 class="font-semibold py-2">Description</h6>
        <p class="py-2">{{ $product->deskripsi }} </p>
    </div>
</div>
