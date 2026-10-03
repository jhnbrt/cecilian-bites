@php($fieldErrors = collect($errors->getMessages())->except(['category', 'table', 'staff', 'status'])->flatten()->unique())

<x-layouts.admin title="Tables">
    <x-admin.page-title title="Tables" subtitle="Dining tables cashiers can assign to dine-in orders" />

    @if ($fieldErrors->isNotEmpty())
        <ul role="alert" class="mb-4 space-y-1 rounded-2xl bg-rose-100 px-4 py-2.5 text-sm text-rose-700">
            @foreach ($fieldErrors as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.tables.store') }}" class="mb-5 flex flex-wrap items-center gap-2 rounded-2xl bg-violet-200 p-3">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}" maxlength="50" required placeholder="Table name, e.g. Table 13" aria-label="Table name" class="min-w-0 flex-1 rounded-xl border-0 bg-white px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
        <input type="number" name="seats" value="{{ old('seats', 4) }}" min="1" max="50" required aria-label="Seats" class="w-24 rounded-xl border-0 bg-white px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
        <button type="submit" class="flex items-center gap-1.5 rounded-full bg-neutral-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-neutral-700">
            <x-icon name="plus" class="size-4" />
            Add table
        </button>
    </form>

    <ul class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($tables as $table)
            @php($isOccupied = $occupiedTableIds->contains($table->id))
            <li class="rounded-2xl bg-white p-3 shadow-sm">
                <div class="mb-2 flex items-center justify-between text-xs">
                    <span class="rounded-full px-2.5 py-1 font-medium {{ $isOccupied ? 'bg-rose-200 text-rose-900' : 'bg-lime-200 text-lime-900' }}">{{ $isOccupied ? 'Occupied' : 'Free' }}</span>
                    <span class="text-neutral-400">{{ $table->orders_count }} {{ \Illuminate\Support\Str::plural('order', $table->orders_count) }} so far</span>
                </div>

                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.tables.update', $table) }}" class="flex min-w-0 flex-1 items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $table->name }}" maxlength="50" required aria-label="Table name" class="min-w-0 flex-1 rounded-xl border-0 bg-neutral-100 px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-violet-400">
                        <input type="number" name="seats" value="{{ $table->seats }}" min="1" max="50" required aria-label="Seats" class="w-16 rounded-xl border-0 bg-neutral-100 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                        <button type="submit" aria-label="Save {{ $table->name }}" class="grid size-9 shrink-0 place-items-center rounded-full bg-violet-300 hover:bg-violet-400">
                            <x-icon name="check" class="size-4" />
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.tables.destroy', $table) }}" onsubmit="return confirm('Delete {{ addslashes($table->name) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" aria-label="Delete {{ $table->name }}" class="grid size-9 place-items-center rounded-full bg-neutral-100 hover:bg-rose-100 hover:text-rose-600">
                            <x-icon name="trash" class="size-4" />
                        </button>
                    </form>
                </div>
            </li>
        @empty
            <li class="col-span-full rounded-2xl bg-white/60 px-4 py-8 text-center text-sm text-neutral-500">No tables yet. Add one above.</li>
        @endforelse
    </ul>
</x-layouts.admin>
