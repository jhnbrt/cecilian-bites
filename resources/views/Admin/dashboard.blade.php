@php($currency = config('restaurant.currency'))

<x-layouts.admin title="Dashboard">
    <x-admin.page-title title="Dashboard" :subtitle="now()->format('l, F j, Y')">
        <a href="{{ route('admin.foods.create') }}" class="flex items-center gap-1.5 rounded-full bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-700">
            <x-icon name="plus" class="size-4" />
            Add dish
        </a>
    </x-admin.page-title>

    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="min-w-0 space-y-5">
            <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                <x-admin.stat label="Today's sales" :value="$currency.number_format($todaySales, 2)" hint="Canceled orders excluded" />
                <x-admin.stat label="Orders today" :value="$todayOrders" />
                <x-admin.stat label="Active orders" :value="$activeOrders" hint="In progress or ready to serve" />
                <x-admin.stat label="Dishes on menu" :value="$availableDishes.' / '.$totalDishes" hint="Available / total" />
            </div>

            <section class="rounded-2xl bg-white p-4 shadow-sm">
                <div class="flex items-baseline justify-between">
                    <h2 class="text-sm font-semibold">Sales · last 7 days</h2>
                    <a href="{{ route('admin.reports.index') }}" class="text-xs font-medium underline underline-offset-2">Full report</a>
                </div>

                <div class="mt-4 flex h-44 items-end gap-3">
                    @foreach ($salesByDay as $day)
                        <div class="flex h-full flex-1 flex-col items-center justify-end gap-1" title="{{ $day['date'] }} · {{ $day['orders'] }} orders">
                            <span class="text-[11px] font-medium text-neutral-500">{{ $day['total'] > 0 ? $currency.number_format($day['total'], 0) : '' }}</span>
                            <div class="w-full rounded-t-xl {{ $loop->last ? 'bg-violet-400' : 'bg-violet-200' }}" style="height: {{ $day['total'] > 0 ? max(3, round($day['total'] / $maxDailySales * 100 * 0.8)) : 1 }}%"></div>
                            <span class="text-[11px] text-neutral-500">{{ $day['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-2xl bg-white p-4 shadow-sm">
                <div class="flex items-baseline justify-between">
                    <h2 class="text-sm font-semibold">Recent orders</h2>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium underline underline-offset-2">All orders</a>
                </div>

                <ul class="mt-3 divide-y divide-neutral-100">
                    @forelse ($recentOrders as $order)
                        <li>
                            <a href="{{ route('admin.orders.show', $order) }}" class="flex flex-wrap items-center gap-x-4 gap-y-1 py-2.5 text-sm hover:bg-neutral-50">
                                <span class="w-16 text-xs text-neutral-400">#{{ $order->number }}</span>
                                <span class="min-w-0 flex-1 truncate font-medium">{{ $order->customer_name }}</span>
                                <span class="text-xs text-neutral-500">{{ $order->diningTable?->name ?? $order->type->label() }}</span>
                                <span class="w-20 text-right font-semibold">{{ $currency }}{{ number_format($order->total, 2) }}</span>
                                <x-admin.status-badge :status="$order->status" />
                            </a>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-neutral-500">No orders yet.</li>
                    @endforelse
                </ul>
            </section>
        </div>

        <aside class="space-y-4 self-start rounded-3xl bg-violet-200 p-4">
            <h2 class="text-lg font-semibold">Top dishes</h2>
            <p class="-mt-3 text-xs text-neutral-600">Most ordered in the last 7 days</p>

            <ol class="space-y-2">
                @forelse ($topDishes as $dish)
                    <li class="flex items-center gap-3 rounded-2xl bg-white p-3 shadow-sm">
                        <span class="grid size-7 shrink-0 place-items-center rounded-full bg-neutral-900 text-xs font-semibold text-white">{{ $loop->iteration }}</span>
                        <span class="min-w-0 flex-1 truncate text-sm font-medium">{{ $dish->name }}</span>
                        <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-medium">{{ (int) $dish->quantity }} sold</span>
                    </li>
                @empty
                    <li class="rounded-2xl bg-white/60 px-4 py-6 text-center text-sm text-neutral-500">No sales yet.</li>
                @endforelse
            </ol>

            <div class="grid grid-cols-2 gap-2 pt-2 text-center text-sm font-medium">
                <a href="{{ route('admin.staff.index') }}" class="rounded-2xl bg-white/70 px-3 py-3 hover:bg-white">
                    <span class="block text-xl font-semibold">{{ $cashierCount }}</span>
                    Cashiers
                </a>
                <a href="{{ route('admin.tables.index') }}" class="rounded-2xl bg-white/70 px-3 py-3 hover:bg-white">
                    <span class="block text-xl font-semibold">&rarr;</span>
                    Tables
                </a>
            </div>
        </aside>
    </div>
</x-layouts.admin>
