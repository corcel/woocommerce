<?php

declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Carbon\Carbon;
use Corcel\Concerns\Aliases;
use Corcel\Concerns\MetaFields;
use Corcel\Model\Post;
use Corcel\WooCommerce\Model\Builder\OrderBuilder;
use Corcel\WooCommerce\Support\Payment;
use Corcel\WooCommerce\Traits\AddressesTrait;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $post_status
 * @property int|null $customer_id
 * @property string|null $currency
 * @property string|null $total
 * @property string|null $shipping
 * @property string|null $tax
 * @property string|null $shipping_tax
 * @property string $status
 * @property Carbon|null $date_completed
 * @property Carbon|null $date_paid
 * @property Payment $payment
 * @property Customer|null $customer
 * @property Collection<int, Item> $items
 */
class Order extends Post
{
    use AddressesTrait;
    use Aliases;

    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    use MetaFields;

    /**
     * The model aliases.
     *
     * @var array<string, array<string, string>>
     */
    protected static $aliases = [
        'customer_id' => ['meta' => '_customer_user'],
    ];

    /**
     * {@inheritDoc}
     *
     * @var array<string>
     */
    protected $appends = [
        'currency',
        'total',
        'shipping',
        'tax',
        'shipping_tax',
        'status',
        'date_completed',
        'date_paid',
        'payment',
    ];

    /**
     * The post type slug.
     *
     * @var string
     */
    protected $postType = 'shop_order';

    /**
     * Create a new factory instance for the model.
     *
     * @return OrderFactory
     */
    protected static function newFactory()
    {
        return OrderFactory::new();
    }

    /**
     * {@inheritDoc}
     */
    public function newEloquentBuilder($builder): OrderBuilder
    {
        return new OrderBuilder($builder);
    }

    /**
     * Get the currency attribute.
     */
    protected function getCurrencyAttribute(): ?string
    {
        $currency = $this->getMeta('_order_currency');

        return is_scalar($currency) ? (string) $currency : null;
    }

    /**
     * Get the total attribute.
     */
    protected function getTotalAttribute(): ?string
    {
        $total = $this->getMeta('_order_total');

        return is_scalar($total) ? (string) $total : null;
    }

    /**
     * Get the shipping attribute.
     */
    protected function getShippingAttribute(): ?string
    {
        $shipping = $this->getMeta('_order_shipping');

        return is_scalar($shipping) ? (string) $shipping : null;
    }

    /**
     * Get the tax attribute.
     */
    protected function getTaxAttribute(): ?string
    {
        $tax = $this->getMeta('_order_tax');

        return is_scalar($tax) ? (string) $tax : null;
    }

    /**
     * Get the shipping tax attribute.
     */
    protected function getShippingTaxAttribute(): ?string
    {
        $shippingTax = $this->getMeta('_order_shipping_tax');

        return is_scalar($shippingTax) ? (string) $shippingTax : null;
    }

    /**
     * Get the status attribute.
     */
    public function getStatusAttribute(): string
    {
        $status = $this->post_status;

        return substr($status, 0, 3) === 'wc-' ? substr($status, 3) : $status;
    }

    /**
     * Get the completed date attribute.
     */
    protected function getDateCompletedAttribute(): ?Carbon
    {
        $value = $this->getMeta('_date_completed');

        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        /**
         * WooCommerce in version 2.6.x has stored completed date in
         * "_completed_date" meta field in MySQL datetime format.
         */
        $value = $this->getMeta('_completed_date');

        return is_string($value)
            ? Carbon::createFromFormat('Y-m-d H:i:s', $value)
            : null;
    }

    /**
     * Get the paid date attribute.
     */
    public function getDatePaidAttribute(): ?Carbon
    {
        $value = $this->getMeta('_date_paid');

        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        /**
         * WooCommerce in version 2.6.x has stored paid date in "_paid_date"
         * meta field in MySQL datetime format.
         */
        $value = $this->getMeta('_paid_date');

        return is_string($value)
            ? Carbon::createFromFormat('Y-m-d H:i:s', $value)
            : null;
    }

    /**
     * Get the payment attribute.
     */
    public function getPaymentAttribute(): Payment
    {
        return new Payment($this);
    }

    /**
     * Get the related customer.
     *
     * @return BelongsTo<Customer, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get related items.
     *
     * @return HasMany<Item, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'order_id');
    }
}
