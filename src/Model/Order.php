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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int|null                                  $customer_id
 * @property string|null                               $currency
 * @property string|null                               $total
 * @property string|null                               $shipping
 * @property string|null                               $tax
 * @property string|null                               $shipping_tax
 * @property string                                    $status
 * @property \Carbon\Carbon|null                       $date_completed
 * @property \Carbon\Carbon|null                       $date_paid
 * @property \Corcel\WooCommerce\Support\Payment       $payment
 * @property \Corcel\WooCommerce\Model\Customer|null   $customer
 * @property \Illuminate\Database\Eloquent\Collection  $items
 */
class Order extends Post
{
    use Aliases;
    use AddressesTrait;
    use MetaFields;

    /**
     * The model aliases.
     *
     * @var  string[][]
     */
    protected static $aliases = [
        'customer_id' => ['meta' => '_customer_user'],
    ];

    /**
     * @inheritDoc
     *
     * @var  string[]
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
        return $this->getMeta('_order_currency');
    }

    /**
     * Get the total attribute.
     *
     * @return  string|null
     */
    protected function getTotalAttribute(): ?string
    {
        return $this->getMeta('_order_total');
    }

    /**
     * Get the shipping attribute.
     *
     * @return  string|null
     */
    protected function getShippingAttribute(): ?string
    {
        return $this->getMeta('_order_shipping');
    }

    /**
     * Get the tax attribute.
     *
     * @return  string|null
     */
    protected function getTaxAttribute(): ?string
    {
        return $this->getMeta('_order_tax');
    }

    /**
     * Get the shipping tax attribute.
     *
     * @return  string|null
     */
    protected function getShippingTaxAttribute(): ?string
    {
        return $this->getMeta('_order_shipping_tax');
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
     * @return \Carbon\Carbon|null
     */
    protected function getDateCompletedAttribute(): ?Carbon
    {
        $value = $this->getMeta('_date_completed');

        if ($value !== null) {
            return Carbon::createFromTimestamp($value);
        }

        /**
         * WooCommerce in version 2.6.x has stored completed date in
         * "_completed_date" meta field in MySQL datetime format.
         */
        $value = $this->getMeta('_completed_date');

        if ($value !== null) {
            $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $value);

            return $datetime !== false ? $datetime : null;
        }

        return null;
    }

    /**
     * Get the paid date attribute.
     *
     * @return \Carbon\Carbon|null
     */
    public function getDatePaidAttribute(): ?Carbon
    {
        $value = $this->getMeta('_date_paid');

        if ($value !== null) {
            return Carbon::createFromTimestamp($value);
        }

        /**
         * WooCommerce in version 2.6.x has stored paid date in "_paid_date"
         * meta field in MySQL datetime format.
         */
        $value = $this->getMeta('_paid_date');

        if ($value !== null) {
            $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $value);

            return $datetime !== false ? $datetime : null;
        }

        return null;
    }

    /**
     * Get the payment attribute.
     *
     * @return \Corcel\WooCommerce\Support\Payment
     */
    public function getPaymentAttribute(): Payment
    {
        return new Payment($this);
    }

    /**
     * Get the related customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get related items.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'order_id');
    }
}
