@extends('shop.layout')
@section('title', 'My Cart · Cecilian Bites')
@section('content')
<link rel="stylesheet" href="{{ asset('cart-enhancements.css') }}">
<div class="cb-cart-page">
  <div class="cb-cart-heading"><div><p class="eyebrow">READY WHEN YOU ARE</p><h1 class="page-title">My Cart <span class="cb-cart-count">{{ count($lines) }} {{ count($lines) === 1 ? 'item' : 'items' }}</span></h1><p class="cb-cart-subtitle">Review your favorites before placing your order.</p></div><a href="{{ route('shop.menu') }}" class="cb-back-link">← Continue shopping</a></div>
  @if(!$lines)
    <div class="cb-cart-empty"><div class="cb-empty-icon">🛒</div><h2>Your cart is empty</h2><p>Something delicious is waiting for you. Browse the menu and add your favorites!</p><a class="button" href="{{ route('shop.menu') }}">Explore the menu →</a></div>
  @else
    <div class="cb-cart-grid"><section class="cb-cart-items" aria-label="Items in your cart"><div class="cb-items-title"><h2>Order items</h2><span>{{ count($lines) }} {{ count($lines) === 1 ? 'product' : 'products' }}</span></div>
    @foreach($lines as $line)
      <article class="cb-cart-item"><div class="cb-item-symbol" aria-hidden="true">🍽️</div><div class="cb-item-detail"><h3>{{ $line['food']->name }}</h3><p class="cb-item-price">₱{{ number_format($line['food']->price, 2) }} / item</p>@if(!$line['food']->is_available)<p class="cb-item-unavailable">Currently unavailable — please remove this item</p>@endif
      <form class="cb-item-controls" method="post" action="{{ route('shop.cart.update') }}">@csrf<input type="hidden" name="menu_item_id" value="{{ $line['food']->id }}"><input type="hidden" name="action" value="set"><label for="cb-qty-{{ $line['food']->id }}">Qty</label><input id="cb-qty-{{ $line['food']->id }}" type="number" name="quantity" min="0" max="99" value="{{ $line['quantity'] }}" required><button type="submit" class="cb-update-btn">Update</button><button type="submit" name="quantity" value="0" class="cb-remove-btn" aria-label="Remove {{ $line['food']->name }}">Remove</button></form></div><strong class="cb-line-total">₱{{ number_format($line['cents']/100, 2) }}</strong></article>
    @endforeach
    <div class="cb-cart-bottom"><a href="{{ route('shop.menu') }}">+ Add more items</a><span>Quantity changes are saved when you select Update.</span></div></section>
    <aside class="cb-cart-summary"><div class="cb-summary-header"><h2>Order summary</h2><span class="cb-summary-tag">Pickup</span></div><p class="cb-summary-desc">Check your order before checkout.</p><div class="cb-summary-row"><span>Subtotal</span><strong>₱{{ number_format(array_sum(array_column($lines, 'cents'))/100, 2) }}</strong></div><div class="cb-summary-row"><span>Items</span><span>{{ array_sum(array_column($lines, 'quantity')) }}</span></div><div class="cb-summary-divider"></div><div class="cb-summary-total"><span>Total</span><strong>₱{{ number_format(array_sum(array_column($lines, 'cents'))/100, 2) }}</strong></div><a class="button cb-checkout-btn" href="{{ route('shop.checkout') }}">Proceed to checkout →</a><p class="cb-summary-note">🔒 Review your pickup and payment details at checkout.</p></aside></div>
  @endif
</div>
@endsection
