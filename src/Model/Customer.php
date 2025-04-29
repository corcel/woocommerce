<?php

declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Model\User;
use Corcel\WooCommerce\Traits\AddressesTrait;
use Corcel\WooCommerce\Traits\HasRelationsThroughMeta;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $order_count
 * @property \Illuminate\Database\Eloquent\Collection<int, Order> $orders
 */
class Customer extends User
{
    use AddressesTrait;

    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    /** @use HasRelationsThroughMeta<\Illuminate\Database\Eloquent\Model, $this> */
    use HasRelationsThroughMeta;

    /**
     * {@inheritDoc}
     *
     * @var array<string>
     */
    protected $appends = [
        'order_count',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return CustomerFactory
     */
    protected static function newFactory()
    {
        return CustomerFactory::new();
    }

    /**
     * Get order count attribute.
     */
    protected function getOrderCountAttribute(): int
    {
        $count = $this->getMeta('_order_count');

        return is_numeric($count) ? (int) $count : 0;
    }

    /**
     * Get the related orders.
     *
     * @return HasMany<\Illuminate\Database\Eloquent\Model, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasManyThroughMeta(
            Order::class,
            '_customer_user',
            'post_id',
            'ID'
        );
    }
}
