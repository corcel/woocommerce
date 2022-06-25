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
 * @property int|null       $customer_id
 * @property string|null    $currency
 * @property string|null    $total
 * @property string|null    $shipping
 * @property string|null    $tax
 * @property string|null    $shipping_tax
 * @property string         $status
 * @property Carbon|null    $date_completed
 * @property Carbon|null    $date_paid
 * @property Payment        $payment
 * @property Customer|null  $customer
 * @property Collection     $items
 */
class Order extends Post
{
    use HasFactory;
    use Aliases;
    use AddressesTrait;
    use MetaFields;

    /**
     * The model aliases.
     *
     * @var  array<string, array<string, string>>
     */
    protected static $aliases = [
        'customer_id' => ['meta' => '_customer_user'],
    ];

    /**
     * @inheritDoc
     *
     * @var  array<string>
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
     * @var  string
     */
    protected $postType = 'shop_order';

    /**
     * Create a new factory instance for the model.
     *
     * @return OrderFactory
     */
    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    /**
     * @inheritDoc
     */
    public function newEloquentBuilder($builder): OrderBuilder
    {
        return new OrderBuilder($builder);
    }

    /**
     * Get the currency attribute.
     *
     * @return  string|null
     */
    protected function getCurrencyAttribute(): ?string
    {
        $currency = $this->getMeta('_order_currency');

        return is_scalar($currency) ? (string) $currency : null;
    }

    /**
     * Get the total attribute.
     *
     * @return  string|null
     */
    protected function getTotalAttribute(): ?string
    {
        $total = $this->getMeta('_order_total');

        return is_scalar($total) ? (string) $total : null;
    }

    /**
     * Get the shipping attribute.
     *
     * @return  string|null
     */
    protected function getShippingAttribute(): ?string
    {
        $shipping = $this->getMeta('_order_shipping');

        return is_scalar($shipping) ? (string) $shipping : null;
    }

    /**
     * Get the tax attribute.
     *
     * @return  string|null
     */
    protected function getTaxAttribute(): ?string
    {
        $tax = $this->getMeta('_order_tax');

        return is_scalar($tax) ? (string) $tax : null;
    }

    /**
     * Get the shipping tax attribute.
     *
     * @return  string|null
     */
    protected function getShippingTaxAttribute(): ?string
    {
        $shippingTax = $this->getMeta('_order_shipping_tax');

        return is_scalar($shippingTax) ? (string) $shippingTax : null;
    }

    /**
     * Get the status attribute.
     *
     * @return  string
     */
    public function getStatusAttribute(): string
    {
        $status = $this->post_status; // @phpstan-ignore-line

        return 'wc-' === substr($status, 0, 3) ? substr($status, 3) : $status;
    }

    /**
     * Get the completed date attribute.
     *
     * @return Carbon|null
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

        if (is_string($value)) {
            $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $value);

            return $datetime !== false ? $datetime : null;
        }

        return null;
    }

    /**
     * Get the paid date attribute.
     *
     * @return Carbon|null
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

        if (is_string($value)) {
            $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $value);

            return $datetime !== false ? $datetime : null;
        }

        return null;
    }

    /**
     * Get the payment attribute.
     *
     * @return Payment
     */
    public function getPaymentAttribute(): Payment
    {
        return new Payment($this);
    }

    /**
     * Get the related customer.
     *
     * @return BelongsTo<Customer, Order>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get related items.
     *
     * @return  HasMany<Item>
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'order_id');
    }
}
