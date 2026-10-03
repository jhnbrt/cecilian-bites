<x-layouts.admin title="Edit dish">
    <x-admin.page-title title="Edit dish" :subtitle="$item->name" />

    @include('Admin.foods._form', ['action' => route('admin.foods.update', $item)])
</x-layouts.admin>
