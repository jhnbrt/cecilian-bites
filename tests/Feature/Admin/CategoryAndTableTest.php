<?php

use App\Enums\OrderStatus;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());
});

test('a category can be created, renamed and deleted', function () {
    $this->post(route('admin.categories.store'), ['name' => 'Noodles'])->assertRedirect();

    $category = Category::query()->where('name', 'Noodles')->sole();
    expect($category->slug)->toBe('noodles');

    $this->put(route('admin.categories.update', $category), ['name' => 'Hot Noodles', 'sort_order' => 3])->assertRedirect();
    expect($category->refresh()->name)->toBe('Hot Noodles')
        ->and($category->slug)->toBe('hot-noodles')
        ->and($category->sort_order)->toBe(3);

    $this->delete(route('admin.categories.destroy', $category))->assertRedirect();
    $this->assertModelMissing($category);
});

test('category names must be unique', function () {
    Category::factory()->create(['name' => 'Drinks', 'slug' => 'drinks']);

    $this->post(route('admin.categories.store'), ['name' => 'Drinks'])->assertSessionHasErrors('name');
    $this->post(route('admin.categories.store'), ['name' => 'drinks!'])->assertSessionHasErrors('name');
});

test('a category that still has dishes cannot be deleted', function () {
    $category = Category::factory()->create();
    MenuItem::factory()->for($category)->create();

    $this->delete(route('admin.categories.destroy', $category))->assertSessionHasErrors('category');

    $this->assertModelExists($category);
});

test('a table can be created, edited and deleted', function () {
    $this->post(route('admin.tables.store'), ['name' => 'Table 20', 'seats' => 8])->assertRedirect();

    $table = DiningTable::query()->where('name', 'Table 20')->sole();
    expect($table->seats)->toBe(8);

    $this->put(route('admin.tables.update', $table), ['name' => 'Patio 1', 'seats' => 2])->assertRedirect();
    expect($table->refresh()->name)->toBe('Patio 1');

    $this->delete(route('admin.tables.destroy', $table))->assertRedirect();
    $this->assertModelMissing($table);
});

test('a table with order history cannot be deleted', function () {
    $order = Order::factory()->create();

    $this->delete(route('admin.tables.destroy', $order->diningTable))->assertSessionHasErrors('table');

    $this->assertModelExists($order->diningTable);
});

test('the tables page shows which tables are occupied', function () {
    $busy = Order::factory()->status(OrderStatus::InProgress)->create();

    $this->get(route('admin.tables.index'))
        ->assertOk()
        ->assertSee($busy->diningTable->name)
        ->assertSee('Occupied');
});
