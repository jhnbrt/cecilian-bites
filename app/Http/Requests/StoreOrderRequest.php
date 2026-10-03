<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Order;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(OrderType::class)],
            'customer_name' => ['required', 'string', 'max:100'],
            'dining_table_id' => [
                Rule::requiredIf(fn (): bool => $this->input('type') === OrderType::DineIn->value),
                'nullable',
                Rule::exists('dining_tables', 'id'),
                function (string $attribute, mixed $value, Closure $fail): void {
                    $isOccupied = Order::query()
                        ->where('dining_table_id', $value)
                        ->whereIn('status', OrderStatus::active())
                        ->exists();

                    if ($isOccupied) {
                        $fail('This table already has an active order.');
                    }
                },
            ],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('menu_items', 'id')->where('is_available', true),
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Add at least one dish to the order.',
            'items.min' => 'Add at least one dish to the order.',
            'items.*.menu_item_id.exists' => 'One of the selected dishes is no longer available.',
            'dining_table_id.required' => 'Select a table for dine-in orders.',
        ];
    }
}
