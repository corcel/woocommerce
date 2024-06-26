<?php

declare(strict_types=1);

namespace Corcel\WooCommerce\Traits;

use Corcel\WooCommerce\Support\BillingAddress;
use Corcel\WooCommerce\Support\ShippingAddress;

/**
 * @property \Corcel\WooCommerce\Support\BillingAddress $billing_address
 * @property \Corcel\WooCommerce\Support\ShippingAddress $shipping_address
 */
trait AddressesTrait
{
    /**
     * Initialize trait.
     */
    protected function initializeAddressesTrait(): void
    {
        $this->appends = array_merge($this->appends, [
            'billing_address',
            'shipping_address',
        ]);
    }

    /**
     * Get the billing address attribute.
     */
    protected function getBillingAddressAttribute(): BillingAddress
    {
        return new BillingAddress($this);
    }

    /**
     * Get the shipping address attribute.
     */
    protected function getShippingAddressAttribute(): ShippingAddress
    {
        return new ShippingAddress($this);
    }
}
