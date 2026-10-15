<article class="cb-order-card">
  <div class="cb-order-card-head"><div><span class="cb-order-id">ORDER #{{ $order->number }}</span><p>{{ $order->created_at->format('M d, Y · h:i A') }}</p></div><span class="cb-order-badge cb-order-badge--{{ $order->status->value }}">{{ $order->status->label() }}</span></div>
  <div class="cb-order-progress" aria-label="Order status">
    @foreach(['Order placed', 'Preparing', 'Ready', 'Completed'] as $stepIndex => $step)
      @php
        $stage = match($order->status->value) { 'in_progress' => 1, 'ready_to_serve' => 2, 'completed' => 3, default => -1 };
      @endphp
      <div class="cb-order-step {{ $stage >= $stepIndex ? 'is-done' : '' }}"><span class="cb-order-dot">{{ $stage >= $stepIndex ? '✓' : $stepIndex + 1 }}</span><small>{{ $step }}</small></div>
    @endforeach
  </div>
  <div class="cb-order-items">
    @foreach($order->items as $item)
      <div><span><b>{{ $item->quantity }}×</b> {{ $item->name }}</span><strong>₱{{ number_format($item->price * $item->quantity, 2) }}</strong></div>
    @endforeach
  </div>
  <div class="cb-order-card-bottom"><div><small>{{ $order->paid_at ? 'Payment recorded' : 'Payment at pickup' }}</small><strong>₱{{ number_format($order->total, 2) }}</strong></div><p>@switch($order->status->value) @case('ready_to_serve') Your order is ready for pickup! @break @case('completed') Thank you for ordering with us! @break @case('canceled') This order was cancelled. @break @default We're preparing your order. @endswitch</p></div>
</article>
