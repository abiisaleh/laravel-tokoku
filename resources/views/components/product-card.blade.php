<a href="/product/{{ $product->id }}" class="group">
    <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-200 xl:aspect-h-8 xl:aspect-w-7">
        <img src="/storage/{{$product->gambar}}"
            alt="{{$product->gambar}}"
            class="h-full w-full object-cover object-center group-hover:opacity-75">
    </div>
    <h3 class="mt-4 text-sm text-gray-700">{{ $product->nama }}</h3>
    <p class="mt-1 text-lg font-medium text-gray-900">Rp. {{ number_format($product->harga) }}</p>
</a>
