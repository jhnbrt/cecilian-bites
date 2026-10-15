@extends('shop.layout')
@section('title', 'My Orders · Cecilian Bites')
@section('content')
<link rel="stylesheet" href="{{ asset('customer-orders.css') }}">
@php
    $activeOrders = $orders->filter(fn ($order) => in_array($order->status->value, ['in_progress', 'ready_to_serve']));
    $finishedOrders = $orders->reject(fn ($order) => in_array($order->status->value, ['in_progress', 'ready_to_serve']));
@endphp
<section class="cb-orders-page">
  <div class="cb-orders-top"><div><p class="cb-orders-kicker">YOUR ORDER HISTORY</p><h1>My Orders</h1><p class="cb-orders-lead">Track your food, review past orders, and see when your pickup is ready.</p></div><a class="cb-orders-refresh" href="{{ route('shop.orders') }}">↻ Refresh orders</a></div>
  <div class="cb-orders-stats"><div><span>Total orders</span><strong>{{ $orders->count() }}</strong></div><div><span>Ongoing</span><strong>{{ $activeOrders->count() }}</strong></div><div><span>Completed</span><strong>{{ $orders->filter(fn ($order) => $order->status->value === 'completed')->count() }}</strong></div></div>
  @if($orders->isNotEmpty())
    <div class="cb-orders-section-heading"><h2>Ongoing orders</h2><span>{{ $activeOrders->count() }} active</span></div>
    @forelse($activeOrders as $order)
      @include('shop.partials.order-card', ['order' => $order])
    @empty <div class="cb-orders-placeholder">No ongoing orders. Your new orders will appear here.</div> @endforelse
    <div class="cb-orders-section-heading"><h2>Order history</h2><span>{{ $finishedOrders->count() }} orders</span></div>
    @forelse($finishedOrders as $order)
      @include('shop.partials.order-card', ['order' => $order])
    @empty <div class="cb-orders-placeholder">Completed and cancelled orders will appear here.</div> @endforelse
  @else
    <div class="cb-orders-empty"><div class="cb-orders-empty-icon">🛍️</div><h2>No orders yet</h2><p>Once you place an order, you can track its progress right here.</p><a class="cb-orders-primary" href="{{ route('shop.menu') }}">Explore the menu →</a></div>
  @endif
  <p class="cb-orders-footnote">Only orders placed in this browser session are shown.</p>
</section>
@endsection
