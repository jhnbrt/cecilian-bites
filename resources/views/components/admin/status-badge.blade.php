@props(['status'])

@use('App\Enums\OrderStatus')

@php
    $colors = match ($status) {
        OrderStatus::InProgress => 'bg-lime-300 text-lime-950',
        OrderStatus::ReadyToServe => 'bg-sky-300 text-sky-950',
        OrderStatus::Canceled => 'bg-rose-300 text-rose-950',
        OrderStatus::Completed => 'bg-neutral-100 text-neutral-500',
    };
@endphp

<span {{ $attributes->class(['inline-block whitespace-nowrap rounded-full px-2.5 py-1 text-[11px] font-medium', $colors]) }}>{{ $status->label() }}</span>
