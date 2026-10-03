<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $type = OrderType::tryFrom((string) $request->query('type'));
        $search = trim((string) $request->query('q', ''));

        $categories = Category::query()
            ->withCount(['menuItems' => fn (Builder $query) => $query->available()])
            ->orderBy('sort_order')
            ->get();

        $activeCategory = $categories->firstWhere('slug', $request->query('category'));

        $menuItems = MenuItem::query()
            ->available()
            ->with('category')
            ->when($activeCategory, fn (Builder $query) => $query->whereBelongsTo($activeCategory))
            ->when($search !== '', fn (Builder $query) => $query->where('name', 'like', '%'.$search.'%'))
            ->orderBy('name')
            ->get();

        $orders = Order::query()
            ->with('diningTable')
            ->withSum('items as items_count', 'quantity')
            ->when($type, fn (Builder $query) => $query->where('type', $type))
            ->latest()
            ->limit(12)
            ->get();

        $orderCounts = Order::query()
            ->toBase()
            ->selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $occupiedTableIds = Order::query()
            ->whereNotNull('dining_table_id')
            ->whereIn('status', OrderStatus::active())
            ->pluck('dining_table_id');

        return view('dashboard', [
            'type' => $type,
            'search' => $search,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'allItemsCount' => $categories->sum('menu_items_count'),
            'menuItems' => $menuItems,
            'orders' => $orders,
            'orderCounts' => $orderCounts,
            'tables' => DiningTable::query()->orderBy('id')->get(),
            'occupiedTableIds' => $occupiedTableIds,
        ]);
    }
}
