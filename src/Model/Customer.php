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
 * @property int         $order_count
 * @property Collection  $orders
 */
class Customer extends User
{
    use HasFactory;
    use AddressesTrait;

    /**
     * @use HasRelationsThroughMeta<\Illuminate\Database\Eloquent\Model>
     */
    use HasRelationsThroughMeta;

    /**
     * @inheritDoc
     *
     * @var  array<string>
     */
    protected $appends = [
        'order_count',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return CustomerFactory
     */
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    /**
     * Get order count attribute.
     *
     * @return  int
     */
    protected function getOrderCountAttribute(): int
    {
        $count = $this->getMeta('_order_count');

        return is_numeric($count) ? (int) $count : 0;
    }

    /**
     * Get the related orders.
     *
     * @return  HasMany<\Illuminate\Database\Eloquent\Model>
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
