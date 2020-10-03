<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Model;

use Corcel\Model\User;
use Corcel\WooCommerce\Traits\AddressesTrait;
use Corcel\WooCommerce\Traits\HasRelationsThroughMeta;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                                       $order_count
 * @property \Illuminate\Database\Eloquent\Collection  $orders
 */
class Customer extends User
{
    use AddressesTrait;
    use HasRelationsThroughMeta;

    /**
     * @inheritDoc
     *
     * @var  string[]
     */
    protected $appends = [
        'order_count',
    ];

    /**
     * Get order count attribute.
     *
     * @return  int
     */
    protected function getOrderCountAttribute(): int
    {
        return (int) $this->getMeta('_order_count');
    }

    /**
     * Get the related orders.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
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
