<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Carbon\CarbonInterface;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'dining_table_id', 'customer_name', 'type', 'status', 'notes', 'total', 'paid_at', 'paid_by', 'cash_received', 'payment_method', 'payment_reference', 'discount', 'amount_paid', 'refunded_at', 'refunded_by', 'refund_reason', 'canceled_at'])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => OrderType::class,
            'status' => OrderStatus::class,
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
            'cash_received' => 'decimal:2',
            'discount' => 'decimal:2', 'amount_paid' => 'decimal:2', 'refunded_at' => 'datetime', 'canceled_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<DiningTable, $this>
     */
    public function diningTable(): BelongsTo
    {
        return $this->belongsTo(DiningTable::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Orders that count towards revenue: everything except canceled orders.
     *
     * @param  Builder<Order>  $query
     */
    public function scopeCountedAsSales(Builder $query): void
    {
        $query->where('status', '!=', OrderStatus::Canceled->value);
    }

    /**
     * @param  Builder<Order>  $query
     */
    public function scopePlacedBetween(Builder $query, CarbonInterface $from, CarbonInterface $to): void
    {
        $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Zero-padded order reference shown on the dashboard, e.g. 000042.
     *
     * @return Attribute<string, never>
     */
    protected function number(): Attribute
    {
        return Attribute::get(fn (): string => str_pad((string) $this->id, 6, '0', STR_PAD_LEFT));
    }
}
