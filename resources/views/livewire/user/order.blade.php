<div>
    <div class="mx-auto max-w-3xl">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Order #OR{{ $order->id }}</h2>

        <div class="mt-6 space-y-4 border-b border-t border-gray-200 py-8 dark:border-gray-700 sm:mt-8">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Billing & Delivery information</h4>

            <dl>
                <dt class="text-base font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</dt>
                <dd class="mt-1 text-base font-normal text-gray-500 dark:text-gray-400">{{ $order->tujuan }}</dd>
            </dl>
        </div>

        <div class="mt-6 sm:mt-8">
            <div class="relative overflow-x-auto border-b border-gray-200 dark:border-gray-800">
                <table class="w-full text-left font-medium text-gray-900 dark:text-white md:table-fixed">
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">

                        @foreach ($order->items as $item)
                            <tr>
                                <td class="whitespace-nowrap py-4 md:w-[384px]">
                                    <div class="flex items-center gap-4">
                                        <a href="#" class="flex items-center aspect-square w-10 h-10 shrink-0">
                                            <img class="h-auto w-full max-h-full dark:hidden"
                                                src="{{ $this->getProductImage($item['id']) }}"
                                                alt="{{ $item['product'] }} image" />
                                        </a>
                                        <a href="/product/{{ $item['id'] }}"
                                            class="hover:underline">{{ $item['product'] }}</a>
                                    </div>
                                </td>

                                <td class="p-4 text-base font-normal text-gray-900 dark:text-white">
                                    x{{ $item['qty'] }}</td>

                                <td class="p-4 text-right text-base font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($item['harga'] * $item['qty']) }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="mt-4 space-y-6">
                <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Order summary</h4>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <dl class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Original price</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">Rp
                                {{ number_format($order->subtotal) }}
                            </dd>
                        </dl>


                        <dl class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Store Pickup</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">Rp
                                {{ number_format($order->ongkir) }}</dd>
                        </dl>

                    </div>

                    <dl
                        class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                        <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                        <dd class="text-lg font-bold text-gray-900 dark:text-white">Rp
                            {{ number_format($order->total) }}</dd>
                    </dl>
                </div>

                <div class="gap-4 sm:flex sm:items-center">

                    @if (in_array($order->status, ['new', 'processing', 'pending']))
                        {{ $this->cancelAction }}
                    @endif

                    {{ $this->downloadAction }}

                </div>
            </div>
        </div>
    </div>

    <x-filament-actions::modals />

</div>
