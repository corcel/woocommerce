<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Carbon\Carbon;
use Corcel\Concerns\MetaFields;
use Corcel\Model\Post;
use Corcel\WooCommerce\Model\Builder\OrderBuilder;
use Corcel\WooCommerce\Support\Payment;
use Corcel\WooCommerce\Traits\AddressesTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \Corcel\WooCommerce\Model\Customer        $customer
 * @property \Carbon\Carbon|null                       $date_completed
 * @property \Carbon\Carbon|null                       $date_paid
 * @property \Illuminate\Database\Eloquent\Collection  $items
 * @property \Corcel\Model\Collection\MetaCollection   $meta
 * @property string                                    $post_status
 * @property string                                    $status
 */
class Order extends Post
{
    use AddressesTrait;
    use MetaFields;

    /**
     * The model aliases.
     *
     * @var  string[][]
     */
    protected static $aliases = [
        'currency'    => ['meta' => '_order_currency'],
        'customer_id' => ['meta' => '_customer_user'],
    ];

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $appends = [
        'status',
        'billing',
        'shipping',
        'payment',
        'customer',
        'date_completed',
        'date_paid',
        'currency',
    ];

    /**
     * The post type slug.
     *
     * @var  string
     */
    protected $postType = 'shop_order';

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $with = [
        'items',
        'customer',
    ];

    /**
     * @inheritDoc
     */
    public function newEloquentBuilder($builder): OrderBuilder
    {
        return new OrderBuilder($builder);
    }

    /**
     * Get the completed date attribute.
     *
     * @return \Carbon\Carbon|null
     */
    protected function getDateCompletedAttribute(): ?Carbon
    {
        $value = $this->meta->_date_completed;

        return !empty($value) ? Carbon::parse($value) : null;
    }

    /**
     * Get the paid date attribute.
     *
     * @return \Carbon\Carbon|null
     */
    public function getDatePaidAttribute(): ?Carbon
    {
        $value = $this->meta->_date_paid;

        return !empty($value) ? Carbon::parse($value) : null;
    }

    /**
     * Get the payment attribute.
     *
     * @return \Corcel\WooCommerce\Support\Payment
     */
    public function getPaymentAttribute(): Payment
    {
        return new Payment($this->meta);
    }

    /**
     * Get status attribute.
     *
     * @return  string
     */
    public function getStatusAttribute(): string
    {
        $status = $this->post_status;

        return 'wc-' === substr($status, 0, 3) ? substr($status, 3) : $status;
    }

    /**
     * Set status attribute.
     *
     * @param  string  $status
     * @return void
     */
    public function setStatusAttribute(string $status): void
    {
        $new_status = 'wc-' === substr($status, 0, 3) ? $status : 'wc-' . $status;

        $this->attributes['post_status'] = $status;
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
