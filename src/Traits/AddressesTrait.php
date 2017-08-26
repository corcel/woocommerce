<?php

namespace Corcel\WooCommerce\Traits;

use Corcel\WooCommerce\Classes\Address;

trait AddressesTrait
{
    public function getBillingAttribute()
    {
        return new Address(static::class, $this->meta, 'billing');
    }

    public function getShippingAttribute()
    {
        return new Address(static::class, $this->meta, 'shipping');
    }
}
