<x-layouts.admin title="Add dish">
    <x-admin.page-title title="Add dish" subtitle="New items appear on the cashier screen right away." />

    @include('Admin.foods._form', ['action' => route('admin.foods.store')])
</x-layouts.admin>
