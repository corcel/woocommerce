<?php
declare(strict_types=1);

namespace Corcel\WooCommerce\Traits;

use Corcel\WooCommerce\Support\BillingAddress;
use Corcel\WooCommerce\Support\ShippingAddress;

/**
 * @property \Corcel\WooCommerce\Support\BillingAddress  $billing_address
 * @property \Corcel\WooCommerce\Support\ShippingAddress  $shipping_address
 */
trait AddressesTrait
{
    /**
     * Get the billing address attribute.
     *
     * @return  \Corcel\WooCommerce\Support\BillingAddress
     */
    public function getBillingAddressAttribute(): BillingAddress
    {
        return new BillingAddress($this, $this->meta);
    }

    /**
     * Get the shipping address attribute.
     *
     * @return  \Corcel\WooCommerce\Support\ShippingAddress
     */
    public function getShippingAddressAttribute(): ShippingAddress
    {
        return new ShippingAddress($this, $this->meta);
    }
}
