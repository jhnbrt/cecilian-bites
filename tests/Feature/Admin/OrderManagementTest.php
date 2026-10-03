<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

test('the orders page lists and filters orders', function () {
    Order::factory()->create(['customer_name' => 'Dine In Dana']);
    Order::factory()->takeAway()->status(OrderStatus::Completed)->create(['customer_name' => 'Takeaway Tom']);

    $this->get(route('admin.orders.index'))
        ->assertOk()
        ->assertSee('Dine In Dana')
        ->assertSee('Takeaway Tom');

    $this->get(route('admin.orders.index', ['type' => 'take_away']))
        ->assertSee('Takeaway Tom')
        ->assertDontSee('Dine In Dana');

    $this->get(route('admin.orders.index', ['status' => 'in_progress']))
        ->assertSee('Dine In Dana')
        ->assertDontSee('Takeaway Tom');

    $this->get(route('admin.orders.index', ['q' => 'tom']))
        ->assertSee('Takeaway Tom')
        ->assertDontSee('Dine In Dana');
});

test('orders can be found by their number', function () {
    $order = Order::factory()->create(['customer_name' => 'Number Nina']);
    Order::factory()->create(['customer_name' => 'Other Olga']);

    $this->get(route('admin.orders.index', ['q' => '#'.$order->number]))
        ->assertSee('Number Nina')
        ->assertDontSee('Other Olga');
});

test('an order page shows its dishes and notes', function () {
    $order = Order::factory()->withItems(2)->create(['customer_name' => 'John Doe', 'notes' => 'No onions']);

    $this->get(route('admin.orders.show', $order))
        ->assertOk()
        ->assertSee('Order #'.$order->number)
        ->assertSee('John Doe')
        ->assertSee('No onions')
        ->assertSee($order->items->first()->name);
});

test('an admin can move an order along from its page', function () {
    $order = Order::factory()->create();

    $this->from(route('admin.orders.show', $order))
        ->patch(route('orders.status', $order), ['status' => 'ready_to_serve'])
        ->assertRedirect(route('admin.orders.show', $order));

    expect($order->refresh()->status)->toBe(OrderStatus::ReadyToServe);
});
