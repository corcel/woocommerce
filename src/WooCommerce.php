<?php

declare(strict_types=1);

namespace Corcel\WooCommerce;

use Corcel\Model\Option;

class WooCommerce
{
    /**
     * The shop currency.
     */
    private static ?string $currency = null;

    /**
     * Get the shop currency.
     */
    public static function currency(): ?string
    {
        if (self::$currency === null) {
            $currency = Option::get('woocommerce_currency');

            self::$currency = is_scalar($currency) ? (string) $currency : '';
        }

        return self::$currency;
    }
}
