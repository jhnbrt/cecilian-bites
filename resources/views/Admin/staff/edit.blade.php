<x-layouts.admin title="Edit staff">
    <x-admin.page-title title="Edit staff" :subtitle="$member->name" />

    @include('Admin.staff._form', ['action' => route('admin.staff.update', $member)])
</x-layouts.admin>
