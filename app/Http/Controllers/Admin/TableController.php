<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DiningTableRequest;
use App\Models\DiningTable;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TableController extends Controller
{
    public function index(): View
    {
        return view('Admin.tables.index', [
            'tables' => DiningTable::query()->withCount('orders')->orderBy('id')->get(),
            'occupiedTableIds' => Order::query()
                ->whereNotNull('dining_table_id')
                ->whereIn('status', OrderStatus::active())
                ->pluck('dining_table_id'),
        ]);
    }

    public function store(DiningTableRequest $request): RedirectResponse
    {
        $table = DiningTable::create($request->validated());

        return back()->with('status', $table->name.' was added.');
    }

    public function update(DiningTableRequest $request, DiningTable $table): RedirectResponse
    {
        $table->update($request->validated());

        return back()->with('status', $table->name.' was updated.');
    }

    public function destroy(DiningTable $table): RedirectResponse
    {
        if ($table->orders()->exists()) {
            return back()->withErrors([
                'table' => $table->name.' has order history, so it cannot be deleted.',
            ]);
        }

        $table->delete();

        return back()->with('status', $table->name.' was deleted.');
    }
}
