<?php

namespace Corcel\WooCommerce\Model;

use Carbon\Carbon;
use Corcel\Model\Post;
use Corcel\WooCommerce\Classes\Payment;
use Corcel\WooCommerce\Builder\OrderBuilder;
use Corcel\WooCommerce\Traits\AddressesTrait;

class Order extends Post
{
    use AddressesTrait;

    /**
     * @var array
     */
    protected static $aliases = [
        'currency'    => ['meta' => '_order_currency'],
        'customer_id' => ['meta' => '_customer_user'],
    ];

    /**
     * @var array
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
     * @var string
     */
    protected $postType = 'shop_order';

    /**
     * @var array
     */
    protected $with = ['items', 'customer'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return Carbon\Carbon|null
     */
    public function getDateCompletedAttribute()
    {
        $value = $this->meta->_date_completed;

        return !empty($value) ? Carbon::parse($value) : null;
    }

    /**
     * @return Carbon\Carbon|null
     */
    public function getDatePaidAttribute()
    {
        $value = $this->meta->_date_paid;

        return !empty($value) ? Carbon::parse($value) : null;
    }

    /**
     * @return Corcel\WooCommerce\Classes\Payment
     */
    public function getPaymentAttribute()
    {
        return new Payment($this->meta);
    }

    /**
     * @return string
     */
    public function getStatusAttribute()
    {
        $status = $this->post_status;

        return 'wc-' === substr($status, 0, 3) ? substr($status, 3) : $status;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'order_id');
    }

    /**
     * @param  $query
     * @return mixed
     */
    public function newEloquentBuilder($query)
    {
        return new OrderBuilder($query);
    }

    /**
     * @param $status
     */
    public function setStatusAttribute($status)
    {
        $new_status = 'wc-' === substr($status, 0, 3) ? $status : 'wc-' . $status;

        $this->attributes['post_status'] = $status;
    }
}
