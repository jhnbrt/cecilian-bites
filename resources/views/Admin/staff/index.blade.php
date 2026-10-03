<x-layouts.admin title="Staff">
    <x-admin.page-title title="Staff" subtitle="Admin and cashier accounts that can log in">
        <a href="{{ route('admin.staff.create') }}" class="flex items-center gap-1.5 rounded-full bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-700">
            <x-icon name="plus" class="size-4" />
            Add staff
        </a>
    </x-admin.page-title>

    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm">
        <table class="w-full min-w-[40rem] text-left text-sm">
            <thead class="text-xs text-neutral-500">
                <tr class="border-b border-neutral-100">
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Email</th>
                    <th class="px-4 py-3 font-medium">Role</th>
                    <th class="px-4 py-3 font-medium">Orders taken</th>
                    <th class="px-4 py-3 font-medium">Joined</th>
                    <th class="px-4 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100">
                @foreach ($users as $member)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-4 py-3 font-medium">
                            {{ $member->name }}
                            @if (auth()->user()->is($member))
                                <span class="ml-1 rounded-full bg-neutral-100 px-2 py-0.5 text-[11px] font-medium text-neutral-500">You</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-neutral-600">{{ $member->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-medium {{ $member->isAdmin() ? 'bg-violet-300 text-violet-950' : 'bg-sky-200 text-sky-950' }}">{{ $member->role->label() }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $member->orders_count }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-neutral-600">{{ $member->created_at->format('M j, Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.staff.edit', $member) }}" aria-label="Edit {{ $member->name }}" class="grid size-8 place-items-center rounded-full bg-violet-300 hover:bg-violet-400">
                                    <x-icon name="pencil" class="size-4" />
                                </a>

                                @unless (auth()->user()->is($member))
                                    <form method="POST" action="{{ route('admin.staff.destroy', $member) }}" onsubmit="return confirm('Remove {{ addslashes($member->name) }}? They will no longer be able to log in.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" aria-label="Delete {{ $member->name }}" class="grid size-8 place-items-center rounded-full bg-neutral-100 hover:bg-rose-100 hover:text-rose-600">
                                            <x-icon name="trash" class="size-4" />
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.admin>
