@extends('shop.layout')
@section('title', 'My Profile · Cecilian Bites')
@section('content')
<div class="cb-profile">
  <div class="cb-profile-heading"><div><p class="eyebrow">YOUR ACCOUNT DETAILS</p><h1 class="page-title">My Profile</h1><p class="muted">Keep your pickup details ready for your next order.</p></div><a class="cb-profile-back" href="{{ route('shop.menu') }}">← Back to menu</a></div>
  <div class="cb-profile-grid">
    <section class="panel cb-profile-card"><div class="cb-profile-avatar">{{ strtoupper(substr($profile['customer_name'] ?? 'G', 0, 1)) }}</div><h2>{{ $profile['customer_name'] ?? 'Guest customer' }}</h2><p class="muted">{{ $profile['phone'] ?? 'No phone number saved yet' }}</p><div class="cb-profile-stat"><strong>{{ $orderCount }}</strong><span>Orders on this browser</span></div><a class="cb-profile-link" href="{{ route('shop.orders') }}">View My Orders →</a></section>
    <section class="panel cb-profile-form"><div class="cb-profile-form-header"><h2>Personal Information</h2><p class="muted">Used to prefill your checkout details.</p></div><form method="POST" action="{{ route('shop.profile.update') }}">@csrf<label>Full name<input name="customer_name" autocomplete="name" maxlength="120" required value="{{ old('customer_name', $profile['customer_name'] ?? '') }}" placeholder="Enter your full name"></label><label>Phone number<input name="phone" type="tel" autocomplete="tel" maxlength="25" required value="{{ old('phone', $profile['phone'] ?? '') }}" placeholder="09XXXXXXXXX"></label><button class="button" type="submit">Save Changes →</button></form><p class="cb-profile-note">This is a guest profile saved in your current browser session, not a permanent login account. Clearing browser session data may remove it. Avoid using this on a shared device.</p></section>
  </div>
</div>
@endsection
