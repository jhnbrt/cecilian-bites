<?php

namespace App\Http\Controllers\Cashier;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $type = $request->enum('type', OrderType::class);

        $lines = collect($request->validated('items'));

        $menuItems = MenuItem::query()
            ->available()
            ->whereIn('id', $lines->pluck('menu_item_id'))
            ->get()
            ->keyBy('id');

        $order = DB::transaction(function () use ($request, $type, $lines, $menuItems): Order {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'dining_table_id' => $type === OrderType::DineIn ? $request->validated('dining_table_id') : null,
                'customer_name' => $request->validated('customer_name'),
                'type' => $type,
                'status' => OrderStatus::InProgress,
                'notes' => $request->validated('notes'),
                'total' => 0,
            ]);

            $total = 0.0;

            foreach ($lines as $line) {
                $menuItem = $menuItems[$line['menu_item_id']];

                $order->items()->create([
                    'menu_item_id' => $menuItem->id,
                    'name' => $menuItem->name,
                    'price' => $menuItem->price,
                    'quantity' => $line['quantity'],
                ]);

                $total += (float) $menuItem->price * $line['quantity'];
            }

            $order->update(['total' => $total]);

            return $order;
        });

        return response()->json([
            'id' => $order->id,
            'number' => $order->number,
            'total' => $order->total,
        ], 201);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $status = $request->enum('status', OrderStatus::class);

        if (! $order->status->canTransitionTo($status)) {
            return back()->withErrors([
                'status' => 'Order #'.$order->number.' cannot move from '.$order->status->label().' to '.$status->label().'.',
            ]);
        }

        $order->update(['status' => $status]);

        return back();
    }
}
